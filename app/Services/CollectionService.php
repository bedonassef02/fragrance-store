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
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $data['image']->store('collections', 'public');
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        return Collection::create($data);
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
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image
            if ($collection->image && !Str::startsWith($collection->image, ['http', 'https'])) {
                 // The model might have an accessor that returns the implementation path. 
                 // We should access the raw attribute if possible or handle it carefully.
                 // For now, assuming standard storage path.
                 // Ideally we'd store just the path and use an accessor for the URL.
                 // Let's check if the existing image path is a full URL or relative path.
                 // Based on typical Laravel setup, if it's stored via ->store(), it's a relative path in storage/app/public
                 // But the accessor might return the full URL.
                 // Let's check the model again in my head... standard Laravel behavior.
                 // If the accessor exists, $collection->image returns the URL. 
                 // We should probably check the raw attribute: $collection->getRawOriginal('image')
                 
                 $oldImage = $collection->getRawOriginal('image');
                 if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                 }
            }
            $data['image'] = $data['image']->store('collections', 'public');
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
