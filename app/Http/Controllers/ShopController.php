<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\ProductService;
use App\Http\Requests\ProductFilterRequest;

class ShopController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(ProductFilterRequest $request)
    {
        $products = $this->productService->getFilteredProducts($request->validated());
        $categories = Category::all();

        return view('shop.index', compact('products', 'categories'));
    }
}
