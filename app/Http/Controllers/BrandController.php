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
        $brands = Brand::withCount('products')->orderBy('name')->get();
        return view('brands.index', compact('brands'));
    }
}
