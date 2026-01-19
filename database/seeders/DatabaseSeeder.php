<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ColorSeeder::class,
            ShopSeeder::class,
            CollectionSeeder::class,
            SettingSeeder::class,
            CouponSeeder::class,
        ]);
    }
}
