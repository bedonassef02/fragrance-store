<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    const DEFAULT_RELATED_PRODUCTS_COUNT = 4;
    const DEFAULT_PAGINATION_COUNT = 12;

    public function getFilteredProducts(array $filters): LengthAwarePaginator
    {
        $query = Product::with(['category', 'variants', 'images']);

        $this->applySearchFilter($query, $filters['search'] ?? null);
        $this->applyCollectionFilter($query, $filters['collection'] ?? null);
        $this->applyCategoryFilter($query, $filters['category'] ?? null);
        $this->applyPriceRangeFilter($query, $filters['price_min'] ?? null, $filters['price_max'] ?? null, $filters['capacity'] ?? null);
        $this->applyBrandFilter($query, $filters['brand'] ?? null);
        
        // Perfume Filters
        $this->applyCapacityFilter($query, $filters['capacity'] ?? null);
        $this->applyConcentrationFilter($query, $filters['concentration'] ?? null);
        $this->applyNoteFilter($query, $filters['notes'] ?? null);
        $this->applyStockFilter($query, $filters['in_stock'] ?? null);
        
        $this->applySorting($query, $filters['sort'] ?? null);

        return $query->paginate(self::DEFAULT_PAGINATION_COUNT)->withQueryString();
    }

    public function getBySlug(string $slug): Product
    {
        return Product::with(['category', 'variants', 'images', 'notes'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getRelatedProducts(Product $product, int $count = self::DEFAULT_RELATED_PRODUCTS_COUNT): \Illuminate\Database\Eloquent\Collection
    {
        // 1. Fetch Candidates (Same Category OR Same Brand) to rank
        // limiting to 40 candidates to ensure performance while giving enough variety
        $candidates = Product::with(['category', 'variants', 'images', 'collections', 'notes'])
            ->where('id', '!=', $product->id)
            ->where(function (Builder $query) use ($product) {
                $query->where('category_id', $product->category_id)
                      ->orWhere('brand_id', $product->brand_id);
            })
            ->take(40) 
            ->select(['id', 'category_id', 'brand_id', 'name', 'slug', 'price', 'original_price', 'image', 'concentration']) // Memory optimization
            ->get();

        // 2. Calculate Similarity Scores
        $scoredCandidates = $candidates->map(function ($candidate) use ($product) {
            $score = 0;

            // Factor A: Same Collection (+50 points) - High Intent
            // Check if they share any collection IDs
            $commonCollections = $candidate->collections->pluck('id')->intersect($product->collections->pluck('id'));
            if ($commonCollections->isNotEmpty()) {
                $score += 50;
            }

            // Factor B: Shared Notes (+10 points per note) - Olfactory Match
            $candidateNotes = $candidate->notes->pluck('name')->toArray();
            $productNotes = $product->notes->pluck('name')->toArray();
            $sharedNotes = array_intersect($candidateNotes, $productNotes);
            $score += count($sharedNotes) * 10;

            // Factor C: Concentration (+5 points)
            if ($candidate->concentration === $product->concentration) {
                $score += 5;
            }

            // Factor D: Category (+5 points)
            if ($candidate->category_id === $product->category_id) {
                $score += 5;
            }

            // Factor E: Price Range (+5 points if within 20%)
            $minPrice = $product->price * 0.8;
            $maxPrice = $product->price * 1.2;
            if ($candidate->price >= $minPrice && $candidate->price <= $maxPrice) {
                $score += 5;
            }

            $candidate->similarity_score = $score;
            return $candidate;
        });

        // 3. Sort by Score DESC and return top N
        $related = $scoredCandidates->sortByDesc('similarity_score')->take($count);

        // 4. Fallback: If we don't have enough products, fill with random ones
        if ($related->count() < $count) {
            $needed = $count - $related->count();
            $excludeIds = $related->pluck('id')->push($product->id)->toArray();

            $fallback = Product::with(['category', 'variants', 'images'])
                ->whereNotIn('id', $excludeIds)
                ->inRandomOrder()
                ->take($needed)
                ->select(['id', 'category_id', 'brand_id', 'name', 'slug', 'price', 'original_price', 'image', 'concentration']) // Memory optimization
                ->get();

            $related = $related->merge($fallback);
        }

        return $related;
    }

    public function getRecentlyViewed(array $ids): \Illuminate\Database\Eloquent\Collection
    {
        if (empty($ids)) {
            return new \Illuminate\Database\Eloquent\Collection();
        }

        $products = Product::with(['category', 'variants', 'images'])
            ->whereIn('id', $ids)
            ->get();
            
        // Sort by the order of IDs in the cookie (most recent first)
        return $products->sortBy(function ($model) use ($ids) {
            return array_search($model->id, $ids);
        });
    }

    public function getFeaturedProducts(int $count = 8): \Illuminate\Database\Eloquent\Collection
    {
        return Product::featured()
            ->with(['category', 'variants', 'images'])
            ->take($count)
            ->get();
    }

    public function getTrendingProducts(int $count = 8): \Illuminate\Database\Eloquent\Collection
    {
        return Product::trending()
            ->with(['category', 'variants', 'images'])
            ->take($count)
            ->get();
    }

    private function applySearchFilter(Builder $query, ?string $search): void
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
    }

    private function applyCollectionFilter(Builder $query, ?string $collectionSlug): void
    {
        if ($collectionSlug) {
            $query->whereHas('collections', function ($q) use ($collectionSlug) {
                $q->where('slug', $collectionSlug);
            });
        }
    }

    private function applyCategoryFilter(Builder $query, $categories): void
    {
        if (!empty($categories)) {
            $categorySlugs = is_array($categories) ? $categories : explode(',', $categories);
            $query->whereHas('category', function ($q) use ($categorySlugs) {
                $q->whereIn('slug', $categorySlugs);
            });
        }
    }

    private function applyPriceRangeFilter(Builder $query, $priceMin, $priceMax, $capacities = null): void
    {
        // If neither min nor max is provided, skip filtering
        if (empty($priceMin) && empty($priceMax)) {
            return;
        }

        // If capacity filter is applied, filter by variant price
        if (!empty($capacities)) {
            $capacityList = is_array($capacities) ? $capacities : explode(',', $capacities);
            
            $query->whereHas('variants', function ($variantQuery) use ($priceMin, $priceMax, $capacityList) {
                $variantQuery->whereIn('capacity', $capacityList);
                
                if (!empty($priceMin)) {
                    $variantQuery->where('price', '>=', (float)$priceMin);
                }
                if (!empty($priceMax)) {
                    $variantQuery->where('price', '<=', (float)$priceMax);
                }
            });
        } else {
            // No capacity filter - use base product price
            if (!empty($priceMin)) {
                $query->where('price', '>=', (float)$priceMin);
            }
            if (!empty($priceMax)) {
                $query->where('price', '<=', (float)$priceMax);
            }
        }
    }

    private function applyCapacityFilter(Builder $query, $capacities): void
    {
        if (!empty($capacities)) {
            $capacityList = is_array($capacities) ? $capacities : explode(',', $capacities);
            $query->whereHas('variants', function ($q) use ($capacityList) {
                $q->whereIn('capacity', $capacityList);
            });
        }
    }

    private function applyConcentrationFilter(Builder $query, $concentrations): void
    {
        if (!empty($concentrations)) {
            $concentrationList = is_array($concentrations) ? $concentrations : explode(',', $concentrations);
            $query->whereIn('concentration', $concentrationList);
        }
    }
    
    private function applyNoteFilter(Builder $query, $notes): void
    {
        if (!empty($notes)) {
            $noteList = is_array($notes) ? $notes : explode(',', $notes);
            $query->whereHas('notes', function ($q) use ($noteList) {
                $q->whereIn('name', $noteList);
            });
        }
    }

    private function applyStockFilter(Builder $query, $inStock): void
    {
        if ($inStock) {
            $query->whereHas('variants', function ($q) {
                $q->where('quantity', '>', 0);
            });
        }
    }

    private function applyBrandFilter(Builder $query, $brands): void
    {
        if (!empty($brands)) {
            $brandSlugs = is_array($brands) ? $brands : explode(',', $brands);
            $query->whereHas('brand', function ($q) use ($brandSlugs) {
                $q->whereIn('slug', $brandSlugs);
            });
        }
    }

    private function applySorting(Builder $query, ?string $sort): void
    {
        if (!$sort) {
            $query->orderBy('created_at', 'desc');
            return;
        }

        $sortFields = explode(',', $sort);
        $allowedSorts = ['price', 'created_at', 'name']; // Define allowed columns for safety

        foreach ($sortFields as $sortField) {
            $direction = 'asc';
            $field = $sortField;

            if (str_starts_with($sortField, '-')) {
                $direction = 'desc';
                $field = substr($sortField, 1);
            }

            if (in_array($field, $allowedSorts)) {
                $query->orderBy($field, $direction);
            }
        }
    }
}
