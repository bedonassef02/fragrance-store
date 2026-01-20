<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Order;
use App\Models\Review;
use App\Models\OrderItem;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::whereIn('status', ['completed'])->with('items.variant.product')->get();

        $comments = [
            5 => [
                "Absolutely stunning! The quality is unmatched.",
                "Perfect fit and the fabric feels so luxurious.",
                "Better than I expected. Will definitely order again.",
                "Elegant and timeless. A masterpeice.",
                "I felt like a queen wearing this. Highly recommended!",
            ],
            4 => [
                "Very beautiful, but the size runs a bit small.",
                "Great quality fabric, delivery was fast.",
                "Lovely design, slightly different color in person but still nice.",
                "Good value for money. Very elegant.",
            ],
            3 => [
                "It's okay, but the material is a bit thinner than expected.",
                "Nice design but the fit was magnificent.",
                "Average quality for the price.",
            ]
        ];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                // 70% chance to review an item
                if (rand(1, 100) > 30) {
                    $rating = rand(3, 5); // Mostly positive reviews
                    
                    // Pick a random comment based on rating
                    $commentList = $comments[$rating] ?? $comments[5];
                    $comment = $commentList[array_rand($commentList)];

                    Review::create([
                        'order_id' => $order->id,
                        'product_id' => $item->variant->product_id,
                        'user_id' => null, // Guest Review (order->user_id is also null)
                        'rating' => $rating,
                        'comment' => $comment,
                        'is_approved' => true,
                    ]);
                }
            }
        }
    }
}
