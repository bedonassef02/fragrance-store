<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Models\Product;
use App\Models\ProductView;

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

        // Log view
        ProductView::create([
            'product_id' => $product->id,
            'ip_address' => request()->ip(),
            'user_id' => auth()->id(),
        ]);

        $product->load(['reviews.user']); // Modified line, removed ->getBySlug($slug)
        $relatedProducts = $this->productService->getRelatedProducts($product);

        $uniqueColors = $product->variants->pluck('color')->unique('id')->filter()->values();
        $allSizes = $product->variants->pluck('size')->unique();

        return view('products.show', compact('product', 'relatedProducts', 'uniqueColors', 'allSizes'));
    }
}
