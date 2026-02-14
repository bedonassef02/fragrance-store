<?php

namespace App\Services;

use Illuminate\Support\Facades\Cookie;
use App\Models\Product;

class WishlistService
{
    protected $cookieName = 'moon_wishlist';
    protected $cookieDuration = 525600; // 1 year in minutes

    /**
     * Get all products in the wishlist
     */
    public function getWishlistProducts()
    {
        $wishlistIds = $this->getWishlistIds();
        if (empty($wishlistIds)) {
            return collect([]);
        }

        return Product::whereIn('id', $wishlistIds)
            ->with(['category', 'images'])
            ->get();
    }

    /**
     * Toggle a product in the wishlist
     * Returns ['status' => 'added'|'removed', 'count' => int]
     */
    public function toggle($productId)
    {
        $wishlistIds = $this->getWishlistIds();
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
        
        $this->updateCookie($wishlistIds);

        return [
            'status' => $status,
            'count' => count($wishlistIds)
        ];
    }

    /**
     * Get raw IDs from cookie
     */
    protected function getWishlistIds()
    {
        $ids = json_decode(Cookie::get($this->cookieName, '[]'), true);
        return is_array($ids) ? $ids : [];
    }

    /**
     * Update the cookie with new IDs
     */
    protected function updateCookie(array $ids)
    {
        $cookie = cookie(
            $this->cookieName, 
            json_encode($ids), 
            $this->cookieDuration,
            '/',           // path
            null,          // domain
            null,          // secure
            false          // httpOnly (FALSE so JS can read it)
        );
        Cookie::queue($cookie);
    }
}
