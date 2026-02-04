<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('data/collections.json'));
        $collections = json_decode($json, true);

        if (!$collections) {
            return;
        }

        foreach ($collections as $c) {
            $c['slug'] = \Illuminate\Support\Str::slug($c['title']);
            $collection = Collection::firstOrCreate(
                ['slug' => $c['slug']],
                $c
            );
            
            // Attach random products (assuming products exist)
            if (\App\Models\Product::exists()) {
                $products = \App\Models\Product::inRandomOrder()->take(rand(4, 12))->pluck('id');
                $collection->products()->attach($products);
            }
        }
    }
}
