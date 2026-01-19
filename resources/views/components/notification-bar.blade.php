@php
    $threshold = \App\Models\Setting::getValue('free_shipping_threshold', 2000);
@endphp

<div class="bg-moon-gold text-moon-dark text-xs font-bold text-center py-2 tracking-widest uppercase relative z-50">
    <span class="inline-block animate-pulse-slow">Free Shipping on orders over {{ number_format($threshold) }} LE</span>
</div>
