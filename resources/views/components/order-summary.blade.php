@props([
    'subtotal',
    'originalSubtotal' => 0,
    'shipping',
    'discount' => 0,
    'total',
    'threshold' => null,
    'coupon' => null,
    'showCouponForm' => false,
])

<div class="bg-neutral-50 p-8 border border-neutral-200 sticky top-28">
    <h3 class="text-charcoal font-serif text-lg mb-6">Order Summary</h3>
    
    <div class="space-y-3 mb-6 text-sm">
        @if($originalSubtotal > $subtotal)
        <div class="flex justify-between text-neutral-500">
            <span>Value of Items</span>
            <span class="line-through">{{ number_format($originalSubtotal) }} LE</span>
        </div>
        <div class="flex justify-between text-accent">
            <span>Product Discounts</span>
            <span>-{{ number_format($originalSubtotal - $subtotal) }} LE</span>
        </div>
        @endif

        <div class="flex justify-between">
            <span class="text-neutral-600">Subtotal</span>
            <span class="text-charcoal font-medium" id="cart-subtotal">{{ number_format($subtotal) }} LE</span>
        </div>
        <div class="flex justify-between">
            <span class="text-neutral-600">Shipping</span>
            <span class="text-charcoal font-medium" id="cart-shipping">{{ $shipping > 0 ? number_format($shipping) . ' LE' : 'Free' }}</span>
        </div>
        @if($shipping > 0 && $threshold && $subtotal < $threshold)
            <p class="text-xs text-neutral-500">Spend <span class="text-accent font-medium">{{ number_format($threshold - $subtotal) }} LE</span> more for free shipping</p>
        @endif
        
        @if(isset($discount) && $discount > 0)
        <div class="flex justify-between text-green-600">
            <span>Coupon Discount</span>
            <span id="cart-discount">-{{ number_format($discount) }} LE</span>
        </div>
        @endif
    </div>
    
    @if($showCouponForm)
    <div class="mb-6 pt-4 border-t border-neutral-200" id="coupon-section">
         @if($coupon)
            <div class="flex justify-between items-center bg-green-50 border border-green-200 p-3">
                <span class="text-green-700 text-sm font-mono">{{ $coupon['code'] }}</span>
                <button type="button" id="remove-coupon-btn" class="text-neutral-500 hover:text-charcoal text-xs uppercase tracking-wider">Remove</button>
            </div>
        @else
            <form id="coupon-form" action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="code" placeholder="Promo Code" class="flex-1 bg-white border border-neutral-300 px-4 py-2.5 text-sm text-charcoal placeholder-neutral-400 focus:border-charcoal outline-none transition-colors" required>
                <button type="submit" class="bg-neutral-100 border border-neutral-300 text-charcoal px-4 py-2.5 text-xs uppercase tracking-wider hover:bg-neutral-200 transition-colors">Apply</button>
            </form>
            <div id="coupon-message" class="mt-2 text-xs font-medium hidden"></div>
        @endif
    </div>
    @endif

    <div class="border-t border-neutral-200 pt-6 mb-6">
        <div class="flex justify-between items-end">
            <span class="text-charcoal font-serif text-lg">Total</span>
            <span class="text-2xl font-serif text-charcoal" id="cart-total">{{ number_format($total) }} LE</span>
        </div>
        <p class="text-xs text-neutral-500 mt-2 text-right">Including VAT</p>
    </div>

    {{ $slot }}
</div>
