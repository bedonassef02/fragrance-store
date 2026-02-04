<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    const DEFAULT_RELATED_PRODUCTS_COUNT = 3;
    const DEFAULT_PAGINATION_COUNT = 12;

    public function getFilteredProducts(array $filters): LengthAwarePaginator
    {
        $query = Product::with(['category', 'variants', 'images']);

        $this->applySearchFilter($query, $filters['search'] ?? null);
        $this->applyCollectionFilter($query, $filters['collection'] ?? null);
        $this->applyCategoryFilter($query, $filters['category'] ?? null);
        $this->applyPriceRangeFilter($query, $filters['price_range'] ?? null);
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
        return Product::with(['category', 'variants', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take($count)
            ->get();
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

    private function applyPriceRangeFilter(Builder $query, $ranges): void
    {
        if (!empty($ranges)) {
            $priceRanges = is_array($ranges) ? $ranges : explode(',', $ranges);
            
            $query->where(function ($q) use ($priceRanges) {
                foreach ($priceRanges as $range) {
                    if (str_contains($range, '+')) {
                        $min = (float) str_replace('+', '', $range);
                        $q->orWhere('price', '>=', $min);
                    } elseif (str_contains($range, '-')) {
                        [$min, $max] = explode('-', $range);
                        $q->orWhereBetween('price', [(float)$min, (float)$max]);
                    }
                }
            });
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
            $brandIds = is_array($brands) ? $brands : explode(',', $brands);
            $query->whereIn('brand_id', $brandIds);
        }
    }

    private function applySorting(Builder $query, ?string $sort): void
    {
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    }
}
