<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);
        
        $subtotal = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
        $shipping = $subtotal > 0 ? 150 : 0; 
        $total = $subtotal + $shipping;

        return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'product_title' => 'nullable|string', 
            'size' => 'required|string',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $productId = $request->product_id;
        $size = $request->size;
        $quantity = $request->quantity ?? 1;

        // Lookup Product (Mock DB)
        $products = ShopController::getProducts();
        $product = collect($products)->firstWhere('id', $productId);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $cart = session()->get('cart', []);
        $key = $productId . '-' . $size;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'key' => $key,
                'product_id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'size' => $size,
                'quantity' => $quantity
            ];
        }

        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Item added to bag',
            'cartCount' => count($cart)
        ]);
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                $cart[$request->id]['quantity'] = $request->quantity;
                session()->put('cart', $cart);
                
                $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
                $shipping = 150;
                $total = $subtotal + $shipping;

                return response()->json([
                    'success' => true, 
                    'subtotal' => number_format($subtotal),
                    'total' => number_format($total)
                ]);
            }
        }
        return response()->json(['success' => false], 400);
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }
}
