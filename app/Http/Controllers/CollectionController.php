<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Collection;

class CollectionController extends Controller
{
    public function index(): View
    {
        $collections = Collection::orderBy('sort_order')->get();
        return view('collections.index', compact('collections'));
    }
}
