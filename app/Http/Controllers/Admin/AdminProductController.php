<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Product;
use App\Services\AdminProductService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    protected $productService;

    public function __construct(AdminProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status']);
        $products = $this->productService->getAllProducts($filters);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $collections = Collection::all();
        $colors = Color::all();
        
        return view('admin.products.create', compact('categories', 'collections', 'colors'));
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->createProduct($request->validated());
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
             return back()->with('error', 'Error creating product: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product)
    {
        $product->load(['collections', 'variants.color', 'images']);
        $categories = Category::all();
        $collections = Collection::all();
        $colors = Color::all();

        return view('admin.products.edit', compact('product', 'categories', 'collections', 'colors'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $this->productService->updateProduct($product, $request->validated());
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating product: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            $this->productService->deleteProduct($product);
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
             return back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }
}
