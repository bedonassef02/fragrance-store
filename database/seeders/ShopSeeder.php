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

        // 2. Sample Products Data (Perfume Focused)
        $products = [
            [
                'name' => 'Sauvage',
                'description' => 'A radically fresh composition, dictated by a name that has the ring of a manifesto.',
                'price' => 4500,
                'original_price' => 5200,
                'image' => 'https://fimgs.net/mdimg/perfume/375x500.31861.jpg',
                'badge' => 'Best Seller',
                'badge_color' => 'bg-blue-600',
                'category' => 'Men',
                'concentration' => 'EDT',
                'gender' => 'male',
                'brand_slug' => 'dior'
            ],
            [
                'name' => 'Baccarat Rouge 540',
                'description' => 'Luminous and sophisticated, Baccarat Rouge 540 lays on the skin like an amber, floral and woody breeze.',
                'price' => 12000,
                'original_price' => null,
                'image' => 'https://fimgs.net/mdimg/perfume/375x500.33519.jpg',
                'badge' => 'Luxury',
                'badge_color' => 'bg-gold-600',
                'category' => 'Niche',
                'concentration' => 'Extrait',
                'gender' => 'unisex',
                'brand_slug' => 'mfk'
            ],
            [
                'name' => 'Aventus',
                'description' => 'The exceptional Aventus was inspired by the dramatic life of a historic emperor, celebrating strength, power and success.',
                'price' => 14500,
                'original_price' => 16000,
                'image' => 'https://fimgs.net/mdimg/perfume/375x500.9828.jpg',
                'badge' => 'Iconic',
                'badge_color' => 'bg-gray-800',
                'category' => 'Men',
                'concentration' => 'EDP',
                'gender' => 'male',
                'brand_slug' => 'creed'
            ],
            [
                'name' => 'Black Opium',
                'description' => 'A captivating floral gourmand scent, twisted with an overdose of black coffee.',
                'price' => 5500,
                'original_price' => null,
                'image' => 'https://fimgs.net/mdimg/perfume/375x500.26378.jpg',
                'badge' => 'Popular',
                'badge_color' => 'bg-pink-600',
                'category' => 'Women',
                'concentration' => 'EDP',
                'gender' => 'female',
                'brand_slug' => 'ysl'
            ],
            [
                'name' => 'Santal 33',
                'description' => 'A unisex fragrance that captures a defining image of the spirit of the American West and personal freedom.',
                'price' => 9800,
                'original_price' => null,
                'image' => 'https://fimgs.net/mdimg/perfume/375x500.12201.jpg',
                'badge' => null,
                'badge_color' => null,
                'category' => 'Unisex',
                'concentration' => 'EDP',
                'gender' => 'unisex',
                'brand_slug' => 'le-labo'
            ],
             [
                'name' => 'Tobacco Vanille',
                'description' => 'Opulent. Warm. Iconic. Reminiscent of an English Gentleman’s Club.',
                'price' => 11000,
                'original_price' => null,
                'image' => 'https://fimgs.net/mdimg/perfume/375x500.1825.jpg',
                'badge' => 'Warm',
                'badge_color' => 'bg-orange-800',
                'category' => 'Unisex',
                'concentration' => 'EDP',
                'gender' => 'unisex',
                'brand_slug' => 'tom-ford'
            ],
        ];

        // Ensure Brands exist
        
        $notes = Note::all();

        foreach ($products as $p) {
            $categoryName = $p['category'];
            $brandSlug = $p['brand_slug'];
            
            unset($p['category']);
            unset($p['brand_slug']);

            $p['slug'] = Str::slug($p['name']);
            $p['category_id'] = $catIds[$categoryName] ?? $catIds['Unisex'];
            $p['type'] = 'original'; // Default type
            
            // Link Brand
            $brand = Brand::where('slug', $brandSlug)->first();
            $p['brand_id'] = $brand ? $brand->id : (Brand::first()->id ?? null);
            
            // Random feature flags
            $p['featured'] = rand(0, 10) > 6;
            $p['trending'] = rand(0, 10) > 7;

            $product = Product::create($p);

            // Attach Random Notes
            if ($notes->count() > 0) {
                // Attach 3-5 random notes
                $product->notes()->attach($notes->random(min($notes->count(), rand(3, 5)))->pluck('id'));
            }

            // Create Variants
            // 1. Original Bottle
            ProductVariant::create([
                'product_id' => $product->id,
                'container_type' => 'Bottle',
                'capacity' => 100,
                'unit' => 'ml',
                'quantity' => 10,
                'price' => $product->price // Base price
            ]);

            ProductVariant::create([
                'product_id' => $product->id,
                'container_type' => 'Bottle',
                'capacity' => 50,
                'unit' => 'ml',
                'quantity' => 15,
                'price' => $product->price - 1000 // Cheaper
            ]);

            // 2. Decants / Samples
            ProductVariant::create([
                'product_id' => $product->id,
                'container_type' => 'Decant',
                'capacity' => 10,
                'unit' => 'ml',
                'quantity' => 50,
                'price' => $product->price * 0.15 // Significantly cheaper
            ]);

             ProductVariant::create([
                'product_id' => $product->id,
                'container_type' => 'Sample',
                'capacity' => 2,
                'unit' => 'ml',
                'quantity' => 100,
                'price' => $product->price * 0.05 // Very cheap
            ]);

            // Create Image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $p['image']
            ]);
            
            $product->update(['image' => $p['image']]);
        }
    }
}
