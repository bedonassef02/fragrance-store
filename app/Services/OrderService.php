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
                ->with('product')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // 2. Validate stock for all items before proceeding.
            foreach ($cartItems as $key => $item) {
                $variant = $variants->get($key);
                if (!$variant || $variant->quantity < $item['quantity']) {
                    $name = $item['name'] . ' (' . $item['size'] . ')';
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
                'subtotal'        => $this->cartService->getSubtotal(),
                'discount_amount' => $this->cartService->getDiscount(),
                'coupon_code'     => $this->cartService->getCoupon()['code'] ?? null,
                'payment_method'  => 'cod',
                'status'          => 'pending',
            ]);

            // 4. Create OrderItems and decrement stock in the same loop.
            foreach ($cartItems as $key => $item) {
                $variant = $variants->get($key);
                
                $order->items()->create([
                    'product_id'         => $variant->product_id, // Link to product
                    'product_variant_id' => $variant->id,
                    'product_name'       => $variant->product->name, // Snapshot name
                    'color'              => $variant->container_type,  // Map container type to color column
                    'size'               => $variant->capacity . ' ' . $variant->unit, // Map capacity/unit to size
                    'unit_price'         => $variant->price ?? $variant->product->price, // Use specific variant price
                    'quantity'           => $item['quantity'],
                    'total'              => ($variant->price ?? $variant->product->price) * $item['quantity'],
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
    public function addDeposit(Order $order, float $amount, $proofFile): void
    {
        // Delete old proof if exists
        if ($order->deposit_proof_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($order->deposit_proof_path);
        }

        $path = $proofFile->store('deposits', 'public');

        $order->update([
            'deposit_amount' => $amount,
            'deposit_proof_path' => $path,
        ]);
    }
    public function deleteDeposit(Order $order): void
    {
        if ($order->deposit_proof_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($order->deposit_proof_path);
        }

        $order->update([
            'deposit_amount' => null,
            'deposit_proof_path' => null,
        ]);
    }
}
