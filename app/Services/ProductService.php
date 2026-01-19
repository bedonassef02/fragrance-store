<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    /**
     * Get filtered products based on criteria.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getFilteredProducts(array $filters): LengthAwarePaginator
    {
        $query = Product::with(['category', 'sizes']);

        // Search
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Category
        if (!empty($filters['category'])) {
            $categories = is_array($filters['category']) ? $filters['category'] : explode(',', $filters['category']);
            $query->whereHas('category', function ($q) use ($categories) {
                $q->whereIn('slug', $categories);
            });
        }

        // Price Range (Predefined Ranges)
        if (!empty($filters['price_range'])) {
            $ranges = is_array($filters['price_range']) ? $filters['price_range'] : explode(',', $filters['price_range']);
            
            $query->where(function($q) use ($ranges) {
                foreach ($ranges as $range) {
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

        // Sizes
        if (!empty($filters['sizes'])) {
            $sizes = is_array($filters['sizes']) ? $filters['sizes'] : explode(',', $filters['sizes']);
            $query->whereHas('sizes', function ($q) use ($sizes) {
                $q->whereIn('size', $sizes);
            });
        }

        // Sorting
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
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
        } else {
            $query->orderBy('created_at', 'desc'); // Default sort
        }

        return $query->paginate(12)->withQueryString();
    }
}
