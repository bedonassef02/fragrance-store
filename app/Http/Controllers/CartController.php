<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Http\Requests\AddToCartRequest;

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

    public function add(AddToCartRequest $request)
    {
        $result = $this->cartService->addToCart($request->validated());
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'cartCount' => $result['cartCount']
            ]);
        }
        
        return response()->json(['error' => $result['message']], $result['status'] ?? 400);
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $result = $this->cartService->updateQuantity($request->id, $request->quantity);
            
            if ($result['success']) {
                return response()->json($result);
            }
        }
        return response()->json(['success' => false], 400);
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $this->cartService->removeItem($request->id);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }

    public function applyCoupon(Request $request)
    {
        $result = $this->cartService->applyCoupon($request->input('code'));

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function removeCoupon()
    {
        $result = $this->cartService->removeCoupon();
        return back()->with('success', $result['message']);
    }
}
