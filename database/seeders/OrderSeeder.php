<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['shipped', 'processing', 'completed', 'cancelled'];
        $faker = \Faker\Factory::create();
        
        // Generate 25 random guest orders
        for ($i = 0; $i < 25; $i++) {
            $status = $statuses[array_rand($statuses)];
            $createdAt = now()->subDays(rand(1, 180));
            
            // Guest Address Data
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            
            $address = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'address' => rand(1, 99) . ' ' . $faker->streetName,
                'city' => 'Cairo',
                'phone' => '010' . rand(10000000, 99999999),
            ];

            $order = Order::create(array_merge($address, [
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => null, // GUEST ORDER
                'email' => strtolower($firstName . '.' . $lastName . '@example.com'),
                'subtotal' => 0,
                'total_amount' => 0,
                'payment_method' => 'credit_card',
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]));

            // Add Items
            $itemCount = rand(1, 4);
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $variant = ProductVariant::inRandomOrder()->with('product', 'color')->first();
                if (!$variant) continue;

                $qty = rand(1, 2);
                $price = $variant->product->price;
                $total = $price * $qty;
                $subtotal += $total;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'color' => $variant->color->name ?? null,
                    'size' => $variant->size,
                    'unit_price' => $price,
                    'quantity' => $qty,
                    'total' => $total
                ]);
            }

            $order->update([
                'subtotal' => $subtotal,
                'total_amount' => $subtotal, 
            ]);
        }
    }
}
