<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        if (empty($cart)) {
            return redirect()->route('cart');
        }

        $subtotal = $this->cartService->getSubtotal();
        $shipping = $this->cartService->getShipping(); 
        $discount = $this->cartService->getDiscount();
        $total = $this->cartService->getTotal();

        return view('checkout.index', compact('cart', 'subtotal', 'shipping', 'discount', 'total'));
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $order = $this->orderService->createOrder($request->validated());
            return redirect()->route('checkout.success', $order->order_number);

        } catch (\Exception $e) {
            return back()->with('error', 'Order processing failed: ' . $e->getMessage())->withInput();
        }
    }

    public function success($orderNumber)
    {
        return view('checkout.success', compact('orderNumber'));
    }
}
