<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Services\ProductService;
use App\Http\Requests\ProductFilterRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function show(string $slug, ProductFilterRequest $request)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
        
        // Merge brand_id into the filter limits to scope the query
        $filters = array_merge($request->validated(), ['brand' => $brand->id]);
        
        $products = $this->productService->getFilteredProducts($filters);
        $categories = Category::all();
        
        // Note: We don't need to fetch brands list for the sidebar since we are on a specific brand page
        
        // Fetch Attributes for Filters (Scoped to this brand could be better, but global is faster for now)
        // TODO: Scope these to the brand for better UX if needed
        $uniqueConcentrations = Product::where('brand_id', $brand->id)
            ->whereNotNull('concentration')
            ->distinct()
            ->orderBy('concentration')
            ->pluck('concentration');

        $uniqueCapacities = ProductVariant::whereHas('product', function($q) use ($brand) {
                $q->where('brand_id', $brand->id);
            })
            ->select('capacity', 'unit')
            ->distinct()
            ->orderBy('capacity')
            ->get()
            ->map(function ($variant) {
                return $variant->capacity . ' ' . $variant->unit;
            })
            ->unique()
            ->values();

        return view('brands.show', compact(
            'brand',
            'products',
            'categories',
            'uniqueConcentrations',
            'uniqueCapacities'
        ));
    }
}
