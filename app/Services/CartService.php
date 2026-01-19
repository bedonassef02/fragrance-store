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
}
