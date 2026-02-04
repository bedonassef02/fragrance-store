<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Note;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $categories = ['Men', 'Women', 'Unisex', 'Niche', 'Designer', 'Best Sellers'];
        $catIds = [];
        foreach ($categories as $cat) {
            $c = Category::firstOrCreate(
                ['slug' => Str::slug($cat)],
                ['name' => $cat]
            );
            $catIds[$cat] = $c->id;
        }

        // 2. Load Products Data from JSON
        $json = file_get_contents(database_path('data/products.json'));
        $products = json_decode($json, true);

        if (!$products) {
            return;
        }

        // Cache Notes for quick lookup
        $allNotes = Note::all()->pluck('id', 'name');

        foreach ($products as $p) {
            $categoryName = $p['category'];
            $brandSlug = $p['brand_slug'];
            $noteNames = $p['notes'] ?? [];
            
            unset($p['category']);
            unset($p['brand_slug']);
            unset($p['notes']);
            $customVariants = $p['variants'] ?? [];
            unset($p['variants']);
            
            $imagesList = $p['images'] ?? [];
            unset($p['images']);

            $p['slug'] = Str::slug($p['name']);
            // Map Niche/Designer to Unisex if not explicit, or handle as tags? 
            // For now, map to Gender category or fallback to Unisex
            $mappedCat = $p['gender'] === 'female' ? 'Women' : ($p['gender'] === 'male' ? 'Men' : 'Unisex');
            // If the JSON category is one of our main categories, use it
            if (isset($catIds[$categoryName])) {
                $p['category_id'] = $catIds[$categoryName];
            } else {
                 $p['category_id'] = $catIds[$mappedCat];
            }
            
            $p['type'] = 'original'; // Default type
            
            // Link Brand
            $brand = Brand::where('slug', $brandSlug)->first();
            $p['brand_id'] = $brand ? $brand->id : (Brand::first()->id ?? null);
            
            // Random feature flags
            $p['featured'] = rand(0, 10) > 6;
            $p['trending'] = rand(0, 10) > 7;

            $product = Product::create($p);

            // Attach Notes
            $noteIdsToAttach = [];
            foreach ($noteNames as $noteName) {
                if (isset($allNotes[$noteName])) {
                    $noteIdsToAttach[] = $allNotes[$noteName];
                }
            }
            if (!empty($noteIdsToAttach)) {
                $product->notes()->attach($noteIdsToAttach);
            }

            // Create Variants
            if (!empty($customVariants)) {
                foreach ($customVariants as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'container_type' => $variant['type'], // 'Bottle', 'Decant', 'Sample'
                        'capacity' => $variant['capacity'],
                        'unit' => $variant['unit'],
                        'quantity' => $variant['quantity'] ?? 50,
                        'price' => $variant['price']
                    ]);
                }
            } else {
                // Default fallback if no variants specified
                 ProductVariant::create([
                    'product_id' => $product->id,
                    'container_type' => 'Bottle',
                    'capacity' => 100,
                    'unit' => 'ml',
                    'quantity' => 10,
                    'price' => $product->price
                ]);
            }

            // Create Images
            $images = !empty($imagesList) ? $imagesList : (isset($p['image']) ? [$p['image']] : []);
            
            foreach ($images as $index => $imgUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imgUrl
                ]);
                
                // Set first image as main if not already set or updated
                if ($index === 0) {
                    $product->update(['image' => $imgUrl]);
                }
            }
        }
    }
}
