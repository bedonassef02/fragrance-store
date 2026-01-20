<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use App\Models\Coupon;

class CartService
{
    public function getCart()
    {
        return session()->get('cart', []);
    }

    public function getSubtotal()
    {
        return collect($this->getCart())->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function getShipping()
    {
        $threshold = \App\Models\Setting::getValue('free_shipping_threshold', 2000);
        
        if ($this->getSubtotal() >= $threshold) {
             return 0;
        }
        return 150;
    }

    public function getCoupon()
    {
        return session()->get('coupon');
    }

    public function getDiscount()
    {
        $coupon = $this->getCoupon();
        if (!$coupon) return 0;

        $subtotal = $this->getSubtotal();
        $discount = 0;

        if ($coupon['type'] === 'fixed') {
            $discount = $coupon['value'];
        } else {
            $discount = $subtotal * ($coupon['value'] / 100);
            if (isset($coupon['max_discount_amount']) && $coupon['max_discount_amount']) {
                $discount = min($discount, $coupon['max_discount_amount']);
            }
        }

        return min($discount, $subtotal);
    }

    public function getTotal()
    {
        return $this->getSubtotal() + $this->getShipping() - $this->getDiscount();
    }

    public function addToCart(array $data)
    {
        $productId = $data['product_id'];
        $size = $data['size'] ?? null;
        $colorName = $data['color'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        $product = \App\Models\Product::find($productId);

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found', 'status' => 404];
        }

        // Validate Variant Logic
        $color = null;
        if ($colorName) {
            $color = \App\Models\Color::where('name', $colorName)->first();
        }
        
        $variantQuery = \App\Models\ProductVariant::where('product_id', $product->id);

        if ($size) {
            $variantQuery->where('size', $size);
        }
        
        if ($color) {
            $variantQuery->where('color_id', $color->id);
        }

        $variant = $variantQuery->first();
        
        $allVariants = $product->variants;
        $hasColors = $allVariants->whereNotNull('color_id')->count() > 0;
        $hasSizes = $allVariants->where('size', '!=', 'One Size')->count() > 0;

        if (!$variant) {
            if ($size || $color) {
                 return ['success' => false, 'message' => 'Selected combination is unavailable.', 'status' => 400];
            }

            if ($hasColors || $hasSizes) {
                return ['success' => false, 'message' => 'Please select options.', 'status' => 400];
            }

            $variant = $allVariants->first();
            if (!$variant) {
                 return ['success' => false, 'message' => 'Product unavailable.', 'status' => 400];
            }
            $size = $variant->size;
        }

        if ($variant && $variant->quantity < $quantity) {
             return ['success' => false, 'message' => "Only {$variant->quantity} items left in stock for this selection.", 'status' => 400];
        }

        $cart = session()->get('cart', []);
        
        $key = $productId . '-' . ($colorName ? \Illuminate\Support\Str::slug($colorName) . '-' : '') . $size;

        $image = $product->image;
        if ($color) {
            $colorImage = $product->images()->where('color_id', $color->id)->first();
            if ($colorImage) {
                $image = $colorImage->image_path;
            }
        }

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'key' => $key,
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $image,
                'size' => $size,
                'color' => $colorName,
                'quantity' => $quantity,
                'variant_id' => $variant->id // Store variant_id for inventory checkout
            ];
        }

        session()->put('cart', $cart);
        
        return ['success' => true, 'message' => 'Item added to bag', 'cartCount' => count($cart)];
    }

    public function updateQuantity($id, $quantity)
    {
        $cart = session()->get('cart');
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
            
            return [
                'success' => true, 
                'subtotal' => number_format($this->getSubtotal()),
                'discount' => number_format($this->getDiscount()),
                'total' => number_format($this->getTotal())
            ];
        }
        return ['success' => false];
    }

    public function removeItem($id)
    {
        $cart = session()->get('cart');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return ['success' => true];
        }
        return ['success' => false];
    }

    public function applyCoupon($code)
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->isValid()) {
             return ['success' => false, 'message' => 'Invalid or expired coupon.'];
        }

        if ($coupon->min_order_amount && $this->getSubtotal() < $coupon->min_order_amount) {
             return ['success' => false, 'message' => 'Order amount must be at least ' . number_format($coupon->min_order_amount)];
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'max_discount_amount' => $coupon->max_discount_amount
        ]);

        return ['success' => true, 'message' => 'Coupon applied successfully!'];
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return ['success' => true, 'message' => 'Coupon removed.'];
    }
}
