<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\ApplyCouponRequest;
use App\Http\Requests\RemoveFromCartRequest;
use App\Http\Requests\UpdateCartRequest;

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
        $shippingThreshold = \App\Models\Setting::getValue('free_shipping_threshold', 2000);
        $coupon = $this->cartService->getCoupon();

        return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'discount', 'total', 'shippingThreshold', 'coupon'));
    }

    public function add(AddToCartRequest $request)
    {
        $result = $this->cartService->addToCart($request->validated());
        
        if ($result['success']) {
            return response()->json($result);
        }
        
        return response()->json(['message' => $result['message']], $result['status'] ?? 400);
    }

    public function update(UpdateCartRequest $request)
    {
        $validated = $request->validated();
        $result = $this->cartService->updateQuantity($validated['id'], $validated['quantity']);
        
        if ($result['success']) {
            return response()->json($result);
        }
        
        return response()->json(['message' => $result['message']], 400);
    }

    public function remove(RemoveFromCartRequest $request)
    {
        $result = $this->cartService->removeItem($request->validated('id'));

        if ($result['success']) {
            return response()->json($result);
        }

        return response()->json(['message' => 'Failed to remove item'], 400);
    }

    public function applyCoupon(ApplyCouponRequest $request)
    {
        $result = $this->cartService->applyCoupon($request->validated('code'));

        if ($request->wantsJson()) {
            if ($result['success']) {
                return response()->json($result);
            }
            return response()->json(['message' => $result['message']], 400);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function removeCoupon()
    {
        $result = $this->cartService->removeCoupon();
        
        if (request()->wantsJson()) {
             return response()->json($result);
        }

        return back()->with('success', $result['message']);
    }
}
