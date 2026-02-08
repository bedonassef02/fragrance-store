<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
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

        // Fetch Perfume Attributes for Filters
        $uniqueConcentrations = Product::whereNotNull('concentration')
            ->distinct()
            ->orderBy('concentration')
            ->pluck('concentration');

        $uniqueCapacities = ProductVariant::select('capacity', 'unit')
            ->distinct()
            ->orderBy('capacity')
            ->get()
            ->map(function ($variant) {
                return $variant->capacity . ' ' . $variant->unit;
            })
            ->unique()
            ->values();

        $activeCollection = null;
        if (!empty($validated['collection'])) {
            $activeCollection = \App\Models\Collection::where('slug', $validated['collection'])->first();
        }

        // Detect if a single brand is selected for banner display
        $activeBrand = null;
        if (!empty($validated['brand']) && count($validated['brand']) === 1) {
            $activeBrand = \App\Models\Brand::where('slug', $validated['brand'][0])->first();
        }

        return view('shop.index', compact(
            'products', 
            'categories', 
            'activeCollection',
            'activeBrand',
            'brands', 
            'uniqueConcentrations', 
            'uniqueCapacities'
        ));
    }
}
