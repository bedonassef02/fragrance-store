<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistIds = json_decode(Cookie::get('moon_wishlist', '[]'), true);
        if (!is_array($wishlistIds)) $wishlistIds = [];

        $products = Product::whereIn('id', $wishlistIds)->with(['category', 'images'])->get();

        return view('wishlist.index', compact('products'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = (int) $request->product_id;
        $wishlistIds = json_decode(Cookie::get('moon_wishlist', '[]'), true);
        if (!is_array($wishlistIds)) $wishlistIds = [];

        $index = array_search($productId, $wishlistIds);
        $status = 'added';

        if ($index !== false) {
            unset($wishlistIds[$index]);
            $status = 'removed';
        } else {
            $wishlistIds[] = $productId;
        }

        // Re-index array
        $wishlistIds = array_values($wishlistIds);

        // Queue cookie for 1 year
        Cookie::queue('moon_wishlist', json_encode($wishlistIds), 60 * 24 * 365);

        return response()->json([
            'status' => $status,
            'count' => count($wishlistIds)
        ]);
    }
}
