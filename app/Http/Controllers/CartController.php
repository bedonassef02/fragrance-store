<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cartItems = $this->cartService->getCart();
        $subtotal = $this->cartService->getSubtotal();
        $shipping = $this->cartService->getShipping();
        $discount = $this->cartService->getDiscount();
        $total = $this->cartService->getTotal();

        return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'discount', 'total'));
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

        // Lookup Product (DB)
        $product = \App\Models\Product::find($productId);

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
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
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
                
                return response()->json([
                    'success' => true, 
                    'subtotal' => number_format($this->cartService->getSubtotal()),
                    'discount' => number_format($this->cartService->getDiscount()),
                    'total' => number_format($this->cartService->getTotal())
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

    public function applyCoupon(Request $request)
    {
        $code = $request->input('code');
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->isValid()) {
             return back()->with('error', 'Invalid or expired coupon.');
        }

        if ($coupon->min_order_amount && $this->cartService->getSubtotal() < $coupon->min_order_amount) {
             return back()->with('error', 'Order amount must be at least ' . number_format($coupon->min_order_amount));
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'max_discount_amount' => $coupon->max_discount_amount
        ]);

        return back()->with('success', 'Coupon applied successfully!');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Coupon removed.');
    }
}
