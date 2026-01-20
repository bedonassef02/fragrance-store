@props([
    'subtotal',
    'shipping',
    'discount' => 0,
    'total',
    'threshold' => null,
    'coupon' => null,
    'showCouponForm' => false,
])

<div class="bg-white/5 p-8 border border-white/10 sticky top-32">
    <h3 class="text-white font-serif text-xl mb-6">Order Summary</h3>
    
    <div class="space-y-4 mb-8 text-sm text-gray-400">
        <div class="flex justify-between">
            <span>Subtotal</span>
            <span class="text-white" id="cart-subtotal">{{ number_format($subtotal) }} LE</span>
        </div>
        <div class="flex justify-between">
            <span>Shipping</span>
            <span class="text-white">{{ $shipping > 0 ? number_format($shipping) . ' LE' : 'Free' }}</span>
        </div>
        @if($shipping > 0 && $threshold && $subtotal < $threshold)
            <p class="text-xs text-gray-500 mt-1">Spend <span class="text-moon-gold">{{ number_format($threshold - $subtotal) }} LE</span> more for free shipping</p>
        @endif
        
        @if(isset($discount) && $discount > 0)
        <div class="flex justify-between text-moon-gold">
            <span>Discount</span>
            <span id="cart-discount">-{{ number_format($discount) }} LE</span>
        </div>
        @endif
    </div>
    
    @if($showCouponForm)
    <div class="mb-6 pt-4 border-t border-gray-800">
         @if($coupon)
            <div class="flex justify-between items-center bg-green-900/30 border border-green-800 p-3 rounded">
                <span class="text-green-400 text-sm font-mono">{{ $coupon['code'] }}</span>
                <form action="{{ route('cart.coupon.remove') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-white text-xs uppercase tracking-wider">Remove</button>
                </form>
            </div>
        @else
            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="code" placeholder="Promo Code" class="flex-1 bg-black/50 border border-gray-700 px-4 py-3 text-sm text-white focus:border-moon-gold outline-none transition-colors">
                <button type="submit" class="bg-gray-800 border border-gray-700 text-white px-6 py-3 text-xs uppercase tracking-widest hover:bg-gray-700 hover:border-gray-600 transition-colors">Apply</button>
            </form>
        @endif
    </div>
    @endif

    <div class="border-t border-gray-700 pt-6 mb-8">
        <div class="flex justify-between items-end">
            <span class="text-white font-serif text-lg">Total</span>
            <span class="text-2xl font-bold text-white" id="cart-total">{{ number_format($total) }} LE</span>
        </div>
        <p class="text-xs text-gray-500 mt-2 text-right">Including VAT</p>
    </div>

    {{ $slot }}
</div>
