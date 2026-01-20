<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Coupon;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function createOrder(array $data): Order
    {
        $cartItems = $this->cartService->getCart();
        
        if (empty($cartItems)) {
            throw new \Exception('Your cart is empty.');
        }

        $variantIds = array_keys($cartItems);

        return DB::transaction(function () use ($data, $cartItems, $variantIds) {
            // 1. Lock and retrieve all variants in a single query to prevent race conditions.
            $variants = ProductVariant::whereIn('id', $variantIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // 2. Validate stock for all items before proceeding.
            foreach ($cartItems as $key => $item) {
                $variant = $variants->get($key);
                if (!$variant || $variant->quantity < $item['quantity']) {
                    $name = $item['name'] . ' (' . $item['size'] . ($item['color'] ? ' - ' . $item['color'] : '') . ')';
                    throw new \Exception("Insufficient stock for {$name}.");
                }
            }

            // 3. Create the Order.
            $order = Order::create([
                'order_number'    => 'ORD-' . strtoupper(uniqid()),
                'user_id'         => Auth::id(),
                'email'           => $data['email'],
                'first_name'      => $data['first_name'],
                'last_name'       => $data['last_name'],
                'address'         => $data['address'],
                'city'            => $data['city'],
                'phone'           => $data['phone'],
                'total_amount'    => $this->cartService->getTotal(),
                'discount_amount' => $this->cartService->getDiscount(),
                'coupon_code'     => $this->cartService->getCoupon()['code'] ?? null,
                'payment_method'  => 'cod',
                'status'          => 'pending',
            ]);

            // 4. Create OrderItems and decrement stock in the same loop.
            foreach ($cartItems as $key => $item) {
                $variant = $variants->get($key);
                
                $order->items()->create([
                    'product_variant_id' => $variant->id,
                    'quantity'           => $item['quantity'],
                    'price'              => $item['price'],
                ]);

                $variant->decrement('quantity', $item['quantity']);
            }

            // 5. Update coupon usage.
            if ($order->coupon_code) {
                Coupon::where('code', $order->coupon_code)->increment('used_count');
            }

            // 6. Clear the cart.
            session()->forget(['cart', 'coupon']);

            return $order;
        });
    }
}
