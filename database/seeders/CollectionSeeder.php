<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        $collections = [
            [
                'title' => 'Ramadan 2026',
                'subtitle' => 'Special Edition',
                'image' => 'https://images.pexels.com/photos/20344409/pexels-photo-20344409/free-photo-of-woman-in-long-coat-posing-in-passage.jpeg?auto=compress&cs=tinysrgb&w=800',
                'route' => 'shop',
                'cta_text' => 'Shop the Look',
                'cta_class' => 'text-white border-b border-white pb-1 hover:text-moon-gold hover:border-moon-gold transition-colors',
                'layout_class' => '',
                'sort_order' => 1
            ],
            [
                'title' => 'Modern Minimalist',
                'subtitle' => 'New Season',
                'image' => 'https://images.pexels.com/photos/16848560/pexels-photo-16848560/free-photo-of-woman-in-dress-in-desert.jpeg?auto=compress&cs=tinysrgb&w=800',
                'route' => 'shop',
                'cta_text' => 'Shop the Look',
                'cta_class' => 'text-white border-b border-white pb-1 hover:text-moon-gold hover:border-moon-gold transition-colors',
                'layout_class' => '',
                'sort_order' => 2
            ],
            [
                'title' => 'The Essentials Edit',
                'subtitle' => 'Everyday Luxury',
                'image' => 'https://images.pexels.com/photos/1152077/pexels-photo-1152077.jpeg?auto=compress&cs=tinysrgb&w=800',
                'route' => 'shop',
                'cta_text' => 'Explore All',
                'cta_class' => 'inline-block bg-white text-black px-8 py-3 uppercase tracking-widest text-xs font-bold hover:bg-moon-gold hover:text-white transition-colors',
                'layout_class' => 'md:col-span-2',
                'sort_order' => 3
            ],
        ];

        foreach ($collections as $c) {
            $c['slug'] = \Illuminate\Support\Str::slug($c['title']);
            $collection = Collection::create($c);
            
            // Attach random products (assuming products exist)
            if (\App\Models\Product::exists()) {
                $products = \App\Models\Product::inRandomOrder()->take(rand(4, 12))->pluck('id');
                $collection->products()->attach($products);
            }
        }
    }
}
