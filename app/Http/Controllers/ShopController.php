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
        $brands = \App\Models\Brand::orderBy('name')->get();
        $notes = \App\Models\Note::orderBy('name')->get();

        $activeCollection = null;
        if (!empty($validated['collection'])) {
            $activeCollection = \App\Models\Collection::where('slug', $validated['collection'])->first();
        }

        return view('shop.index', compact('products', 'categories', 'activeCollection', 'brands', 'notes'));
    }
}
