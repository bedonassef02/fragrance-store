<?php

namespace App\Services;

use App\Models\Order;
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

    public function createOrder(array $data)
    {
        $cart = $this->cartService->getCart();
        
        if (empty($cart)) {
            throw new \Exception('Your cart is empty');
        }

        $total = $this->cartService->getTotal();
        $discount = $this->cartService->getDiscount();
        $couponCode = session('coupon.code');

        DB::beginTransaction();

        try {
            // Verify Stock for all items FIRST before creating order
            foreach ($cart as $item) {
                // If we stored variant_id in cart, use it. If not, re-query (fallback for old sessions)
                // In our updated CartService, we store variant_id.
                
                $variantId = $item['variant_id'] ?? null;
                
                if (!$variantId) {
                     // Fallback logic to find variant if not in session keys (Safety net)
                     // Logic similar to CartService::addToCart resolution could go here
                     // But strictly speaking, we should have it.
                     // For now, let's assume if it's not there, we skip strict stock check or fail?
                     // Better fail to be safe.
                     // Actually, let's try to resolve it if missing, or throw error.
                     // To keep it clean, let's assume new cart items have it.
                     // If legacy cart item lacking variant_id, we might have an issue.
                     // Let's assume re-add to cart is needed or look it up.
                     
                     // Lookup:
                     $product = \App\Models\Product::find($item['product_id']);
                     $color = $item['color'] ? \App\Models\Color::where('name', $item['color'])->first() : null;
                     $variantQuery = ProductVariant::where('product_id', $item['product_id'])->where('size', $item['size']);
                     if($color) $variantQuery->where('color_id', $color->id);
                     $variant = $variantQuery->first();
                     $variantId = $variant ? $variant->id : null;
                }

                if ($variantId) {
                    $variant = ProductVariant::lockForUpdate()->find($variantId); // Lock row
                    if (!$variant || $variant->quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$item['name']} ({$item['size']}" . ($item['color'] ? " - {$item['color']}" : "") . ")");
                    }
                    $variant->decrement('quantity', $item['quantity']);
                }
            }

            $order = Order::create([
                'order_number'   => 'ORD-' . strtoupper(uniqid()),
                'user_id'        => Auth::id(), // Nullable
                'email'          => $data['email'],
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'address'        => $data['address'],
                'city'           => $data['city'],
                'phone'          => $data['phone'],
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
                    'color'      => $item['color'] ?? null,
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

            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
