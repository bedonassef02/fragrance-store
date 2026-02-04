<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Models\Product;
use App\Models\ProductView;

use Illuminate\Support\Facades\Cookie;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function show(string $slug)
    {
        $product = $this->productService->getBySlug($slug);

        // Recently Viewed Cookie Logic
        $recentIds = json_decode(Cookie::get('recently_viewed', '[]'), true);
        if (!is_array($recentIds)) $recentIds = [];
        
        // Add current product to start
        array_unshift($recentIds, $product->id);
        // Remove duplicates and keep indices re-indexed
        $recentIds = array_values(array_unique($recentIds));
        // Keep only last 10
        $recentIds = array_slice($recentIds, 0, 10);
        
        // Queue cookie for 30 days (minutes)
        Cookie::queue('recently_viewed', json_encode($recentIds), 60 * 24 * 30);

        // Log view
        ProductView::create([
            'product_id' => $product->id,
            'ip_address' => request()->ip(),
            'user_id' => auth()->id(),
        ]);

        $product->load(['reviews.user', 'notes']); 
        $relatedProducts = $this->productService->getRelatedProducts($product);

        // Perfume specific variants extraction
        $uniqueCapacities = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->capacity, // Key by capacity
                'label' => $variant->capacity . ' ' . $variant->unit,
                'capacity' => $variant->capacity,
                'unit' => $variant->unit
            ];
        })->unique('id')->values();

        return view('products.show', compact('product', 'relatedProducts', 'uniqueCapacities'));
    }
    public function reviews(string $slug)
    {
        $product = $this->productService->getBySlug($slug);
        $reviews = $product->reviews()->with('user')->paginate(4); // 4 reviews per page
        return response()->json($reviews);
    }
}
