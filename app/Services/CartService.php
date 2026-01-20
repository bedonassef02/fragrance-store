<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'cart';
    private const COUPON_KEY = 'coupon';

    public function getCart()
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function getSubtotal(): float
    {
        return collect($this->getCart())->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function getShipping(): float
    {
        $threshold = Cache::remember('settings.free_shipping_threshold', 3600, function () {
            return Setting::getValue('free_shipping_threshold', 2000);
        });

        return $this->getSubtotal() >= $threshold ? 0 : 150.0;
    }

    public function getCoupon()
    {
        return Session::get(self::COUPON_KEY);
    }

    public function getDiscount(): float
    {
        $coupon = $this->getCoupon();
        if (!$coupon) return 0;

        $subtotal = $this->getSubtotal();
        
        $discount = match ($coupon['type']) {
            'fixed' => (float) $coupon['value'],
            default => $subtotal * ((float) $coupon['value'] / 100),
        };

        if (isset($coupon['max_discount_amount'])) {
            $discount = min($discount, (float) $coupon['max_discount_amount']);
        }

        return min($discount, $subtotal);
    }

    public function getTotal(): float
    {
        return $this->getSubtotal() + $this->getShipping() - $this->getDiscount();
    }
    
    private function getCartState(): array
    {
        $cart = $this->getCart();
        return [
            'totals' => [
                'subtotal' => $this->getSubtotal(),
                'discount' => $this->getDiscount(),
                'total' => $this->getTotal(),
            ],
            'cartCount' => count($cart),
        ];
    }

    public function addToCart(array $data)
    {
        $variantId = $data['product_variant_id'];
        $quantity = $data['quantity'];
        
        $variant = ProductVariant::with('product.images')->findOrFail($variantId);

        if ($variant->quantity < $quantity) {
            return ['success' => false, 'message' => "Only {$variant->quantity} items left in stock.", 'status' => 400];
        }

        $cart = $this->getCart();
        $key = (string) $variantId;

        $image = $variant->product->image;
        if ($variant->color_id) {
            $colorImage = $variant->product->images->firstWhere('color_id', $variant->color_id);
            if ($colorImage) {
                $image = $colorImage->image_path;
            }
        }
        
        if (isset($cart[$key])) {
            $newQuantity = $cart[$key]['quantity'] + $quantity;
            if ($variant->quantity < $newQuantity) {
                 return ['success' => false, 'message' => "Not enough stock. Only {$variant->quantity} available.", 'status' => 400];
            }
            $cart[$key]['quantity'] = $newQuantity;
        } else {
            $cart[$key] = [
                'key'        => $key,
                'variant_id' => $variant->id,
                'product_id' => $variant->product->id,
                'name'       => $variant->product->name,
                'price'      => $variant->product->price,
                'image'      => $image,
                'size'       => $variant->size,
                'color'      => $variant->color?->name,
                'quantity'   => $quantity,
            ];
        }

        Session::put(self::SESSION_KEY, $cart);
        
        return ['success' => true, 'message' => 'Item added to bag', 'cartCount' => count($cart)];
    }

    public function updateQuantity(string $variantId, int $quantity)
    {
        $cart = $this->getCart();
        
        if (!isset($cart[$variantId])) {
            return ['success' => false, 'message' => 'Item not found in cart.'];
        }

        $variant = ProductVariant::find($variantId);
        if (!$variant || $variant->quantity < $quantity) {
            return ['success' => false, 'message' => 'Not enough stock available.'];
        }

        $cart[$variantId]['quantity'] = $quantity;
        Session::put(self::SESSION_KEY, $cart);
            
        return ['success' => true] + $this->getCartState();
    }

    public function removeItem(string $variantId)
    {
        $cart = $this->getCart();
        unset($cart[$variantId]);
        Session::put(self::SESSION_KEY, $cart);
        
        return ['success' => true] + $this->getCartState();
    }

    public function applyCoupon(string $code)
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->isValid()) {
             return ['success' => false, 'message' => 'Invalid or expired coupon.'];
        }

        if ($coupon->min_order_amount && $this->getSubtotal() < $coupon->min_order_amount) {
             return ['success' => false, 'message' => 'Order total must be at least ' . number_format($coupon->min_order_amount)];
        }

        Session::put(self::COUPON_KEY, [
            'code'                => $coupon->code,
            'type'                => $coupon->type,
            'value'               => $coupon->value,
            'max_discount_amount' => $coupon->max_discount_amount
        ]);

        return ['success' => true, 'message' => 'Coupon applied successfully!'];
    }

    public function removeCoupon()
    {
        Session::forget(self::COUPON_KEY);
        return ['success' => true, 'message' => 'Coupon removed.'];
    }
}
