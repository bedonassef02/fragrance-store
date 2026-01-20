<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Collection;
use App\Models\Product; // Add Product usage if needed, though usually via relationship

class CollectionController extends Controller
{
    public function index(): View
    {
        $collections = Collection::orderBy('sort_order')->get();
        return view('collections.index', compact('collections'));
    }
}
