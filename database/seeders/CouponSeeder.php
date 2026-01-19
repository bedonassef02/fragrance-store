<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percent',
            'value' => 10, // 10%
            'max_discount_amount' => 500, // Max 500 LE discount
            'usage_limit' => 100, // First 100 users
            'min_order_amount' => 1000,
            'valid_from' => now(),
            'valid_to' => now()->addMonths(1),
        ]);

        \App\Models\Coupon::create([
            'code' => 'FIXED50',
            'type' => 'fixed',
            'value' => 50, // 50 LE off
            'min_order_amount' => 500,
        ]);
    }
}
