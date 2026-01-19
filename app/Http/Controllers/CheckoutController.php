<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
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

    public function store(Request $request)
    {
        $request->validate([
            'email'      => 'required|email',
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'address'    => 'required|string|max:255',
            'city'       => 'required|string|max:255',
            'phone'      => 'required|string|max:20',
        ]);

        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        $total = $this->cartService->getTotal();
        $discount = $this->cartService->getDiscount();
        $couponCode = session('coupon.code');

        DB::beginTransaction();

        try {
            $order = Order::create([
                'order_number'   => 'ORD-' . strtoupper(uniqid()),
                'user_id'        => Auth::id(), // Nullable
                'email'          => $request->email,
                'first_name'     => $request->first_name,
                'last_name'      => $request->last_name,
                'address'        => $request->address,
                'city'           => $request->city,
                'phone'          => $request->phone,
                'total_amount'   => $total,
                'discount_amount'=> $discount,
                'coupon_code'    => $couponCode,
                'payment_method' => 'cod',
                'status'         => 'pending',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'size'       => $item['size'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }

            // Coupon Usage Increment
            if ($couponCode) {
                \App\Models\Coupon::where('code', $couponCode)->increment('used_count');
            }

            DB::commit();

            // Clear Cart and Coupon
            session()->forget(['cart', 'coupon']);

            return redirect()->route('checkout.success', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Order processing failed. Please try again. ' . $e->getMessage());
        }
    }

    public function success($orderNumber)
    {
        return view('checkout.success', compact('orderNumber'));
    }
}
