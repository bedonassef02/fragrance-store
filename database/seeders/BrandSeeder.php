<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('data/brands.json'));
        $brands = json_decode($json, true);

        if (!$brands) {
             // Fallback if file not found or empty
            $brands = [
                ['name' => 'Dior', 'slug' => 'dior', 'is_luxury' => true],
            ];
        }

        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['slug' => $brand['slug']],
                ['name' => $brand['name'], 'is_luxury' => $brand['is_luxury']]
            );
        }
    }
}
