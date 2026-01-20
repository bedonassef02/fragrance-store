<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Get all categories with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return Category::orderBy('name')->paginate($perPage);
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return Category::create($data);
    }

    /**
     * Update a category.
     *
     * @param Category $category
     * @param array $data
     * @return bool
     */
    public function update(Category $category, array $data): bool
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $category->update($data);
    }

    /**
     * Delete a category.
     *
     * @param Category $category
     * @return bool|null
     */
    public function delete(Category $category): ?bool
    {
        return $category->delete();
    }
}
