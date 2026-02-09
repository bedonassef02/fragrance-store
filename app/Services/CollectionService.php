<?php

namespace App\Services;

use App\Models\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CollectionService
{
    /**
     * Get all collections with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return Collection::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Create a new collection.
     *
     * @param array $data
     * @return Collection
     */
    public function create(array $data): Collection
    {
        // Remove image from data as we handle it via Media Library
        $image = $data['image'] ?? null;
        unset($data['image']);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $collection = Collection::create($data);

        if ($image instanceof \Illuminate\Http\UploadedFile) {
            $collection->addMedia($image)
                       ->toMediaCollection('banner');
        }

        return $collection;
    }

    /**
     * Update a collection.
     *
     * @param Collection $collection
     * @param array $data
     * @return bool
     */
    public function update(Collection $collection, array $data): bool
    {
        $image = $data['image'] ?? null;
        unset($data['image']);

        if ($image instanceof \Illuminate\Http\UploadedFile) {
            $collection->addMedia($image)
                       ->toMediaCollection('banner');
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        return $collection->update($data);
    }

    /**
     * Delete a collection.
     *
     * @param Collection $collection
     * @return bool|null
     */
    public function delete(Collection $collection): ?bool
    {
        if ($collection->getRawOriginal('image')) {
             Storage::disk('public')->delete($collection->getRawOriginal('image'));
        }

        return $collection->delete();
    }
}
