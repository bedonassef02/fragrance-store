<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Order;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function create(Request $request, Order $order)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired review link.');
        }

        $order->load(['items.variant.product.images', 'items.variant.color']);

        return view('reviews.create', compact('order'));
    }

    public function store(StoreReviewRequest $request, Order $order)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired review link.');
        }

        $this->reviewService->createReviews($request->validated(), $order);

        return redirect()->route('home')->with('success', 'Thank you for your feedback!');
    }
}
