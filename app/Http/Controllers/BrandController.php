<?php

namespace App\Http\Controllers;

use App\Models\Brand;

class BrandController extends Controller
{
    /**
     * Display a listing of all local brands.
     */
    public function index()
    {
        $brands = Brand::where('is_local', true)
                      ->withCount('products')
                      ->orderBy('name')
                      ->paginate(12);
        return view('brands.index', compact('brands'));
    }
}
