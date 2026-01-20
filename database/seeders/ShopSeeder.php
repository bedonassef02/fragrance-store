<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = ['Abayas', 'Kaftans', 'Bags', 'Dresses'];
        $catIds = [];
        foreach ($categories as $cat) {
            $c = Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat)
            ]);
            $catIds[$cat] = $c->id;
        }

        // Products
        $products = [
            [
                'name' => 'Royal Black Abaya',
                'description' => 'Exquisitely crafted from premium nida fabric, this Royal Black Abaya features intricate gold embroidery along the cuffs and hem.',
                'price' => 2800,
                'original_price' => 3500,
                'image' => 'https://images.pexels.com/photos/9940866/pexels-photo-9940866.jpeg?auto=compress&cs=tinysrgb&w=800',
                'badge' => '-20%',
                'badge_color' => 'bg-red-600',
                'category' => 'Abayas'
            ],
            [
                'name' => 'Crimson Velvet Kaftan',
                'description' => 'A statement piece for evening wear, this Crimson Velvet Kaftan drapes elegantly with a luxurious sheen.',
                'price' => 4200,
                'original_price' => null,
                'image' => 'https://images.pexels.com/photos/28905393/pexels-photo-28905393/free-photo-of-elegant-woman-in-red-traditional-dress-in-marrakech.jpeg?auto=compress&cs=tinysrgb&w=800',
                'badge' => null,
                'badge_color' => null,
                'category' => 'Kaftans'
            ],
            [
                'name' => 'Embossed Leather Clutch',
                'description' => 'Detailed embossed leather clutch with gold hardware.',
                'price' => 1850,
                'original_price' => null,
                'image' => 'https://images.pexels.com/photos/1152077/pexels-photo-1152077.jpeg?auto=compress&cs=tinysrgb&w=800',
                'badge' => 'New',
                'badge_color' => 'bg-moon-gold',
                'category' => 'Bags'
            ],
            [
                'name' => 'Desert Rose Dress',
                'description' => 'Inspired by the hues of the desert sunset, this dress features flowing chiffon layers.',
                'price' => 3100,
                'original_price' => null,
                'image' => 'https://images.pexels.com/photos/16848560/pexels-photo-16848560/free-photo-of-woman-in-dress-in-desert.jpeg?auto=compress&cs=tinysrgb&w=800',
                'badge' => null,
                'badge_color' => null,
                'category' => 'Dresses'
            ],
             [
                'name' => 'Midnight Silk Abaya',
                'description' => 'Deep midnight blue silk abaya that shimmers under the evening light.',
                'price' => 2950,
                'original_price' => null,
                'image' => 'https://images.pexels.com/photos/20344409/pexels-photo-20344409/free-photo-of-woman-in-long-coat-posing-in-passage.jpeg?auto=compress&cs=tinysrgb&w=800',
                'badge' => null,
                'badge_color' => null,
                'category' => 'Abayas'
            ],
            [
                'name' => 'Gold Chain Satchel',
                'description' => 'Compact yet spacious satchel with a signature gold chain strap.',
                'price' => 1870,
                'original_price' => 2200,
                'image' => 'https://images.pexels.com/photos/298863/pexels-photo-298863.jpeg?auto=compress&cs=tinysrgb&w=800',
                'badge' => '-15%',
                'badge_color' => 'bg-red-600',
                'category' => 'Bags'
            ]
        ];

        foreach ($products as $p) {
            $categoryName = $p['category'];
            unset($p['category']);
            $p['slug'] = Str::slug($p['name']);
            $p['category_id'] = $catIds[$categoryName] ?? null;
            $p['featured'] = rand(0, 10) > 6; // 40% chance
            $p['trending'] = rand(0, 10) > 7; // 30% chance

            $product = Product::create($p);

            // Variants (Colors + Sizes)
            if ($categoryName !== 'Bags') {
                $selectedColors = \App\Models\Color::inRandomOrder()->take(2)->get();
                $sizes = ['S', 'M', 'L'];
                
                foreach ($selectedColors as $color) {
                    foreach ($sizes as $size) {
                        \App\Models\ProductVariant::create([
                            'product_id' => $product->id,
                            'color_id' => $color->id,
                            'size' => $size,
                            'quantity' => 20
                        ]);
                    }

                    // Add Specific Image for this color
                     \App\Models\ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => "https://placehold.co/800x1200/1a1a1a/c6a87c?text=" . urlencode($color->name) . "+Detail",
                        'color_id' => $color->id
                    ]);
                }
            } else {
                // Bags: 1 Color (Random)
                $color = \App\Models\Color::inRandomOrder()->first();
                \App\Models\ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $color->id,
                    'size' => 'One Size',
                    'quantity' => 10
                ]);
            }

            // General Gallery Images (No specific color)
            \App\Models\ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'https://placehold.co/800x1200/1a1a1a/c6a87c?text=General+Detail'
            ]);
        }
    }
}
