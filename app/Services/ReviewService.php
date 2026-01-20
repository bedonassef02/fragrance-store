<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class ReviewService
{
    public function createReviews(array $data, Order $order): void
    {
        foreach ($data['reviews'] as $productId => $reviewData) {
            $imagePath = null;
            
            // Handle image upload if present
            if (isset($reviewData['image']) && $reviewData['image'] instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = $reviewData['image']->store('reviews', 'public');
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
                    'image_path' => $imagePath,
                    // is_approved defaults to false in database/model
                ]
            );
        }
    }

    public function getFilteredReviews(string $status = 'all', int $perPage = 10): LengthAwarePaginator
    {
        $query = Review::with(['product', 'user', 'order']);

        if ($status === 'pending') {
            $query->where('is_approved', false);
        } elseif ($status === 'approved') {
            $query->where('is_approved', true);
        }

        return $query->latest()->paginate($perPage);
    }

    public function updateStatus(Review $review, bool $isApproved): bool
    {
        return $review->update(['is_approved' => $isApproved]);
    }

    public function deleteReview(Review $review): ?bool
    {
        if ($review->image_path) {
            Storage::disk('public')->delete($review->image_path);
        }

        return $review->delete();
    }
}
