<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreWishlistRequest;

class WishlistController extends Controller
{
    protected $wishlistService;

    public function __construct(\App\Services\WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }
    public function index()
    {
        $products = $this->wishlistService->getWishlistProducts();
        return view('wishlist.index', compact('products'));
    }

    public function toggle(StoreWishlistRequest $request)
    {
        $result = $this->wishlistService->toggle((int) $request->product_id);

        return response()->json($result);
    }
}
