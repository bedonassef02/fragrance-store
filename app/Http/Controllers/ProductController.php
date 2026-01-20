<?php

namespace App\Http\Controllers;

use App\Services\ProductService;

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
        $relatedProducts = $this->productService->getRelatedProducts($product);

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
