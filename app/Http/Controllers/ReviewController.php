<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function create(Request $request, Order $order)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired review link.');
        }

        $order->load(['items.variant.product.images', 'items.variant.color']);

        return view('reviews.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired review link.');
        }

        $validated = $request->validate([
            'reviews' => 'required|array',
            'reviews.*.product_id' => 'required|exists:products,id',
            'reviews.*.rating' => 'required|integer|min:1|max:5',
            'reviews.*.comment' => 'nullable|string|max:1000',
            'reviews.*.image' => 'nullable|image|max:2048'
        ]);

        foreach ($validated['reviews'] as $productId => $reviewData) {
            $imagePath = null;
            if ($request->hasFile("reviews.{$productId}.image")) {
                $imagePath = $request->file("reviews.{$productId}.image")->store('reviews', 'public');
            }

            Review::updateOrCreate(
                [
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'user_id' => $order->user_id // Nullable if guest
                ],
                [
                    'rating' => $reviewData['rating'],
                    'comment' => $reviewData['comment'] ?? null,
                    'image_path' => $imagePath
                ]
            );
        }

        return redirect()->route('home')->with('success', 'Thank you for your feedback!');
    }
}
