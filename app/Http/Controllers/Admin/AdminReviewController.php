<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product', 'order'])->latest();

        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }

        $reviews = $query->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function update(Request $request, Review $review)
    {
        $review->update([
            'is_approved' => !$review->is_approved
        ]);

        $status = $review->is_approved ? 'approved' : 'hidden';
        return back()->with('success', "Review has been {$status}.");
    }

    public function destroy(Review $review)
    {
        if ($review->image_path && Storage::disk('public')->exists($review->image_path)) {
            Storage::disk('public')->delete($review->image_path);
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
