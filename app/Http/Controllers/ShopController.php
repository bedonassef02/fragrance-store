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
        $validated = $request->validated();
        $products = $this->productService->getFilteredProducts($validated);
        $categories = Category::all();
        $colors = \App\Models\Color::has('variants')->get(); // Only colors attached to products
        $sizes = \App\Models\ProductVariant::select('size')->distinct()->whereNotNull('size')->pluck('size');

        $activeCollection = null;
        if (!empty($validated['collection'])) {
            $activeCollection = \App\Models\Collection::where('slug', $validated['collection'])->first();
        }

        return view('shop.index', compact('products', 'categories', 'activeCollection', 'colors', 'sizes'));
    }
}
