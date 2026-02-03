<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BrandSeeder::class, // NEW
            NoteSeeder::class, // NEW
            ShopSeeder::class,
            CollectionSeeder::class,
            SettingSeeder::class,
            CouponSeeder::class,
            OrderSeeder::class, // Orders need Users and Products
            ReviewSeeder::class, // Reviews need Orders and Products
        ]);
    }
}
