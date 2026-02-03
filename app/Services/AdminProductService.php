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

            // 5. Handle Images
            if (!empty($data['images'])) {
                foreach ($data['images'] as $imageItem) {
                    if (isset($imageItem['file'])) {
                        $image = $imageItem['file'];
                        $path = $image->store('products', 'public');
                        
                        // Set main image if not set
                        if (!$product->image) {
                            $product->update(['image' => $path]);
                        }
                        
                        $product->images()->create([
                            'image_path' => $path,
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

            // 5. Handle New Images
            if (!empty($data['new_images'])) {
                foreach ($data['new_images'] as $imageItem) {
                    if (isset($imageItem['file'])) {
                        $image = $imageItem['file'];
                        $path = $image->store('products', 'public');
                        $product->images()->create([
                            'image_path' => $path,
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
