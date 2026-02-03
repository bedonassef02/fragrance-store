<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Dior', 'slug' => 'dior', 'is_luxury' => true],
            ['name' => 'Chanel', 'slug' => 'chanel', 'is_luxury' => true],
            ['name' => 'Tom Ford', 'slug' => 'tom-ford', 'is_luxury' => true],
            ['name' => 'Creed', 'slug' => 'creed', 'is_luxury' => true],
            ['name' => 'Maison Francis Kurkdjian', 'slug' => 'mfk', 'is_luxury' => true],
            ['name' => 'Byredo', 'slug' => 'byredo', 'is_luxury' => true],
            ['name' => 'Le Labo', 'slug' => 'le-labo', 'is_luxury' => true],
            ['name' => 'Yves Saint Laurent', 'slug' => 'ysl', 'is_luxury' => true],
            ['name' => 'Local Artisan', 'slug' => 'local-artisan', 'is_luxury' => false], // Example local brand
            ['name' => 'Moon Essence', 'slug' => 'moon-essence', 'is_luxury' => false], // Our generic local brand
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
