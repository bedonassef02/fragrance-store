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
    public function getAllProducts($perPage = 10)
    {
        return Product::with(['category', 'variants'])
            ->latest()
            ->paginate($perPage);
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
                'featured' => $data['featured'] ?? false,
                'trending' => $data['trending'] ?? false,
                'badge' => $data['badge'] ?? null,
                'badge_color' => $data['badge_color'] ?? null,
            ]);

            // 2. Handle Collections
            if (!empty($data['collections'])) {
                $product->collections()->sync($data['collections']);
            }

            // 3. Handle Variants
            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    $product->variants()->create([
                        'size' => $variantData['size'],
                        'color_id' => $variantData['color_id'],
                        'quantity' => $variantData['quantity'],
                    ]);
                }
            }

            // 4. Handle Images
            if (!empty($data['images'])) {
                foreach ($data['images'] as $imageItem) {
                    if (isset($imageItem['file'])) {
                        $image = $imageItem['file'];
                        $colorId = $imageItem['color_id'] ?? null;
                        
                        $path = $image->store('products', 'public');
                        
                        // For now, setting the first image as the main image string on the product model (legacy support)
                        if (!$product->image) {
                            $product->update(['image' => $path]);
                        }
                        
                        $product->images()->create([
                            'image_path' => $path,
                            'color_id' => $colorId,
                        ]);
                    }
                }
            }

            return $product;
        });
    }

    public function updateProduct(Product $product, array $data)
    {
        return DB::transaction(function () use ($product, $data) {
            // 1. Update Product Details
            $product->update([
                'name' => $data['name'],
                // Only update slug if name changed significantly, or keep as is? 
                // Usually better not to change slug often for SEO, but let's allow it if requested or just keep it simple.
                // 'slug' => ... 
                'description' => $data['description'],
                'price' => $data['price'],
                'original_price' => $data['original_price'] ?? null,
                'category_id' => $data['category_id'],
                'featured' => $data['featured'] ?? false,
                'trending' => $data['trending'] ?? false,
                'badge' => $data['badge'] ?? null,
                'badge_color' => $data['badge_color'] ?? null,
            ]);

            // 2. Sync Collections
            if (isset($data['collections'])) {
                $product->collections()->sync($data['collections']);
            }

            // 3. Handle Variants (Sync/Update/Create)
            // Strategy: Detach or Delete all and recreate? Or smart sync?
            // For MVP, deleting all and recreating is unsafe if orders depend on them (Wait, orders link to variants...).
            // We should ideally update existing ones and create new ones.
            // Simplified approach for now:
            
            // Delete removed variants?
            // Let's assume the UI sends the full state of variants.
            
            // For now, let's just create new ones passed in 'new_variants' and update existing if 'variants' is passed with IDs.
            // But to keep it simple as per "simple Admin", let's assume we can add new variants or edit stock of existing.
            
            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    if (isset($variantData['id'])) {
                        // Update
                        $variant = ProductVariant::find($variantData['id']);
                        if ($variant && $variant->product_id == $product->id) {
                            $variant->update([
                                'size' => $variantData['size'],
                                'color_id' => $variantData['color_id'],
                                'quantity' => $variantData['quantity'],
                            ]);
                        }
                    } else {
                        // Create
                        $product->variants()->create([
                            'size' => $variantData['size'],
                            'color_id' => $variantData['color_id'],
                            'quantity' => $variantData['quantity'],
                        ]);
                    }
                }
            }
            
            // Handle Variant Deletions if IDs are provided
            if (!empty($data['deleted_variants'])) {
                 ProductVariant::destroy($data['deleted_variants']);
            }

            // 4. Handle New Images
            if (!empty($data['new_images'])) {
                foreach ($data['new_images'] as $imageItem) {
                    if (isset($imageItem['file'])) {
                        $image = $imageItem['file'];
                        $colorId = $imageItem['color_id'] ?? null;

                        $path = $image->store('products', 'public');
                        $product->images()->create([
                            'image_path' => $path,
                            'color_id' => $colorId
                        ]);
                        
                        // Update main image if none exists
                        if (!$product->image) {
                            $product->update(['image' => $path]);
                        }
                    }
                }
            }

             // Handle Image Deletions
             if (!empty($data['deleted_images'])) {
                $imagesToDelete = ProductImage::whereIn('id', $data['deleted_images'])->get();
                foreach($imagesToDelete as $img) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
                // Check if main image was deleted and replace it with another one if available
                if (in_array($product->image, $imagesToDelete->pluck('image_path')->toArray())) {
                     $nextImage = $product->images()->first();
                     $product->update(['image' => $nextImage ? $nextImage->image_path : null]);
                }
             }

            return $product;
        });
    }

    public function deleteProduct(Product $product)
    {
        return DB::transaction(function () use ($product) {
            // Delete images from storage
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            
            // Delete product (cascade will handle variants/images records in DB if set up, but let's be safe)
            $product->images()->delete();
            $product->variants()->delete();
            $product->delete();
        });
    }
}
