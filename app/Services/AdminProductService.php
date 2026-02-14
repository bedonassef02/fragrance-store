<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductService
{
    public function getAllProducts($filters = [], $perPage = 10)
    {
        $query = Product::with(['category', 'variants']);

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'out_of_stock') {
                $query->whereDoesntHave('variants', function($q) {
                    $q->where('quantity', '>', 0);
                });
            } elseif ($filters['status'] === 'featured') {
                $query->where('featured', true);
            }
        }

        return $query->latest()->paginate($perPage);
    }

    public function createProduct(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Create Product
            $product = Product::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']) . '-' . Str::random(6),
                'description' => $data['description'],
                'price' => $data['price'],
                'original_price' => $data['original_price'] ?? null,
                'category_id' => $data['category_id'],
                'brand_id' => $data['brand_id'],
                'featured' => $data['featured'] ?? false,
                'trending' => $data['trending'] ?? false,
                'badge' => $data['badge'] ?? null,
                'badge_color' => $data['badge_color'] ?? null,
                
                // Perfume Attributes
                'concentration' => $data['concentration'] ?? null,
                'gender' => $data['gender'],
                'type' => $data['type'] ?? 'original',
                'inspired_by' => $data['inspired_by'] ?? null,
                'original_product_id' => $data['original_product_id'] ?? null,
                'image' => '', // Default value to satisfy database constraint
            ]);

            // 2. Handle Collections
            if (!empty($data['collections'])) {
                $product->collections()->sync($data['collections']);
            }
            
            // 3. Handle Notes
            if (!empty($data['notes'])) {
                $product->notes()->sync($data['notes']);
            }

            // 4. Handle Variants
            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    $product->variants()->create([
                        'capacity' => $variantData['capacity'],
                        'unit' => $variantData['unit'],
                        'container_type' => $variantData['container_type'],
                        'price' => $variantData['price'],
                        'quantity' => $variantData['quantity'],
                    ]);
                }
            }

            // 5. Handle Images (Spatie Media Library)
            if (!empty($data['images'])) {
                foreach ($data['images'] as $imageItem) {
                    if (isset($imageItem['file'])) {
                        $product->addMedia($imageItem['file'])
                                ->toMediaCollection('default');
                    }
                }
            }
            
            // Legacy support: specific main image handling if needed, 
            // but we'll prefer the media library's 'default' collection for main images.
            // If the frontend expects 'image' column to be populated, we might need a listener or observer to sync, 
            // but for now we will rely on the media library. 
            // Ideally, we should stop writing to 'image' column if we fully migrate.
            // However, to keep safety as per plan:
             if (!empty($data['images']) && !$product->image) {
                 // We can get the URL of the first media item
                 $mediaItem = $product->getFirstMedia('default');
                 if ($mediaItem) {
                     // We store the relative path or full URL depending on how the app uses it.
                     // The original code stored 'products/filename.jpg'. 
                     // Spatie stores in 'storage/media/id/filename.jpg'.
                     // Let's store the relative path for compatibility if needed, 
                     // or just leave it since we'll upgrade the frontend.
                     // For now, let's NOT write to the old column to avoid confusion, 
                     // effectively enforcing the migration to media library.
                 }
             }

            // Invalidate Cache for Filters
            \Illuminate\Support\Facades\Cache::forget('shop_filters_concentrations');
            \Illuminate\Support\Facades\Cache::forget('shop_filters_capacities');

            return $product;
        });
    }

    public function updateProduct(Product $product, array $data)
    {
        return DB::transaction(function () use ($product, $data) {
            // 1. Update Product Details
            $product->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'original_price' => $data['original_price'] ?? null,
                'category_id' => $data['category_id'],
                'brand_id' => $data['brand_id'],
                'featured' => $data['featured'] ?? false,
                'trending' => $data['trending'] ?? false,
                'badge' => $data['badge'] ?? null,
                'badge_color' => $data['badge_color'] ?? null,
                 // Perfume Attributes
                'concentration' => $data['concentration'] ?? null,
                'gender' => $data['gender'],
                'type' => $data['type'] ?? 'original',
                'inspired_by' => $data['inspired_by'] ?? null,
                'original_product_id' => $data['original_product_id'] ?? null,
            ]);

            // 2. Sync Collections
            if (isset($data['collections'])) {
                $product->collections()->sync($data['collections']);
            }
            
            // 3. Sync Notes
            if (isset($data['notes'])) {
                $product->notes()->sync($data['notes']);
            }

            // 4. Handle Variants (Sync/Update/Create)
            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    if (isset($variantData['id'])) {
                        // Update
                        $variant = ProductVariant::find($variantData['id']);
                        if ($variant && $variant->product_id == $product->id) {
                            $variant->update([
                                'capacity' => $variantData['capacity'],
                                'unit' => $variantData['unit'],
                                'container_type' => $variantData['container_type'],
                                'price' => $variantData['price'],
                                'quantity' => $variantData['quantity'],
                            ]);
                        }
                    } else {
                        // Create
                        $product->variants()->create([
                            'capacity' => $variantData['capacity'],
                            'unit' => $variantData['unit'],
                            'container_type' => $variantData['container_type'],
                            'price' => $variantData['price'],
                            'quantity' => $variantData['quantity'],
                        ]);
                    }
                }
            }
            
            // Handle Variant Deletions
            if (!empty($data['deleted_variants'])) {
                 ProductVariant::destroy($data['deleted_variants']);
            }

            // 5. Handle New Images (Spatie Media Library)
            if (!empty($data['new_images'])) {
                foreach ($data['new_images'] as $imageItem) {
                    if (isset($imageItem['file'])) {
                        $product->addMedia($imageItem['file'])
                                ->toMediaCollection('default');
                    }
                }
            }

            // Handle Media Deletions (Spatie)
            if (!empty($data['deleted_media_ids'])) {
                \Spatie\MediaLibrary\MediaCollections\Models\Media::whereIn('id', $data['deleted_media_ids'])
                    ->where('model_id', $product->id) // Security check: ensure ownership
                    ->where('model_type', Product::class)
                    ->delete();
            }

            // Handle Legacy Image Deletions (Optimized with Cursor)
            if (!empty($data['deleted_legacy_image_ids'])) {
                // Use cursor to minimize memory usage for large deletions
                ProductImage::whereIn('id', $data['deleted_legacy_image_ids'])
                    ->where('product_id', $product->id)
                    ->cursor() // <-- Optimization: Streams results one by one
                    ->each(function ($img) {
                        if (Storage::disk('public')->exists($img->image_path)) {
                             Storage::disk('public')->delete($img->image_path);
                        }
                        $img->delete();
                    });
            }

            // Fallback for backward compatibility (if needed, but safer to deprecate)
            if (!empty($data['deleted_images'])) {
                 // Try to guess or log warning. For safety, we will NOT delete indiscriminately.
                 // Assuming frontend will migrate to the new keys.
            }

            // Invalidate Cache for Filters
            \Illuminate\Support\Facades\Cache::forget('shop_filters_concentrations');
            \Illuminate\Support\Facades\Cache::forget('shop_filters_capacities');

            return $product;
        });
    }

    public function deleteProduct(Product $product)
    {
        return DB::transaction(function () use ($product) {
            // Delete images from storage (Optimized)
            // Use cursor to avoid loading all images into memory if there are many
            $product->images()->cursor()->each(function ($image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            });
            
            // Delete records
            $product->images()->delete();
            $product->variants()->delete();
            $product->delete();

            // Invalidate Cache for Filters
            \Illuminate\Support\Facades\Cache::forget('shop_filters_concentrations');
            \Illuminate\Support\Facades\Cache::forget('shop_filters_capacities');
        });
    }

    /**
     * Bulk Delete Products (Optimized)
     */
    public function bulkDelete(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            // Process in chunks of 100 to avoid memory overload
            Product::whereIn('id', $ids)->chunkById(100, function ($products) {
                foreach ($products as $product) {
                    /** @var Product $product */
                    $this->deleteProduct($product);
                }
            });
        });
    }
}
