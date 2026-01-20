<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::setValue('free_shipping_threshold', 2000);
        \App\Models\Setting::setValue('home_hero_image', 'images/hero_generated.png');
    }
}
