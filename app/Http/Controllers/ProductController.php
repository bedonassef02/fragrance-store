<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Fetch specific product logic or just reuse the first one for now if ID logic is complex/mocked
        // For better simulation, let's find it in our mock array
        $products = \App\Http\Controllers\ShopController::getProducts();
        
        $product = collect($products)->firstWhere('id', $id);

        // Fallback for demo if id not found (or just use the first one)
        if (!$product) {
            $product = $products[0];
        }

        // Mock Related products (just excluding current)
        $relatedProducts = collect($products)->reject(fn($p) => $p['id'] == $product['id'])->take(4);

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
