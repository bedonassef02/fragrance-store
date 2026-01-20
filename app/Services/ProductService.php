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
        $query = Product::with(['category', 'variants.color']);

        $this->applySearchFilter($query, $filters['search'] ?? null);
        $this->applyCategoryFilter($query, $filters['category'] ?? null);
        $this->applyPriceRangeFilter($query, $filters['price_range'] ?? null);
        $this->applySizeFilter($query, $filters['sizes'] ?? null);
        $this->applySorting($query, $filters['sort'] ?? null);

        return $query->paginate(self::DEFAULT_PAGINATION_COUNT)->withQueryString();
    }

    public function getBySlug(string $slug): Product
    {
        return Product::with('category', 'variants.color', 'images.color')
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getRelatedProducts(Product $product, int $count = self::DEFAULT_RELATED_PRODUCTS_COUNT): \Illuminate\Database\Eloquent\Collection
    {
        return Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
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

    private function applySizeFilter(Builder $query, $sizes): void
    {
        if (!empty($sizes)) {
            $sizeList = is_array($sizes) ? $sizes : explode(',', $sizes);
            $query->whereHas('variants', function ($q) use ($sizeList) {
                $q->whereIn('size', $sizeList);
            });
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
