@extends('layouts.app')

@section('title', 'Shopping Cart | MOON')

@section('content')
    <div class="pt-28 pb-24 bg-cream min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-accent uppercase tracking-[0.3em] text-xs font-medium mb-3">Your Selection</p>
                <h1 class="text-3xl md:text-4xl font-serif text-charcoal">Shopping Bag</h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 cart-container">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-6" id="cart-items">
                    @forelse($cartItems as $variantId => $item)
                    <!-- Item -->
                    <div class="cart-item-row flex gap-6 border-b border-neutral-200 pb-6" id="row-{{ $variantId }}">
                        <a href="{{ route('product.show', $item['slug']) }}" class="w-24 md:w-32 h-32 md:h-40 bg-neutral-100 flex-shrink-0 block overflow-hidden">
                            <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                        </a>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start">
                                    <a href="{{ route('product.show', $item['slug']) }}">
                                        <h3 class="text-charcoal font-serif text-lg hover:text-accent transition-colors">{{ $item['name'] }}</h3>
                                    </a>
                                    <div class="text-right">
                                        <span class="text-charcoal font-medium">{{ number_format($item['price']) }} LE</span>
                                        @if(isset($item['original_price']) && $item['original_price'] > $item['price'])
                                            <span class="text-neutral-400 line-through text-sm block">{{ number_format($item['original_price']) }} LE</span>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-neutral-500 text-sm mt-2">
                                    @if(isset($item['concentration']) && $item['concentration'])
                                    <span class="text-accent/80 uppercase text-[10px] tracking-wider">{{ $item['concentration'] }}</span>
                                    <span class="mx-1">·</span>
                                    @endif
                                    {{ $item['capacity'] }}
                                </p>
                            </div>
                            
                            <div class="flex justify-between items-center mt-4">
                                <div class="flex items-center border border-neutral-300">
                                    <button class="cart-qty-btn px-3 py-1.5 text-neutral-500 hover:text-charcoal hover:bg-neutral-100 transition-colors" data-action="decrease" data-id="{{ $variantId }}">−</button>
                                    <span class="cart-qty-display px-3 text-charcoal text-sm font-medium" id="qty-{{ $variantId }}">{{ $item['quantity'] }}</span>
                                    <button class="cart-qty-btn px-3 py-1.5 text-neutral-500 hover:text-charcoal hover:bg-neutral-100 transition-colors" data-action="increase" data-id="{{ $variantId }}">+</button>
                                </div>
                                <button class="cart-remove-btn text-neutral-500 text-xs uppercase tracking-wider hover:text-red-500 transition-colors" data-id="{{ $variantId }}">Remove</button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-16 border border-neutral-200 bg-neutral-50">
                        <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <p class="text-neutral-500 mb-4">Your cart is empty</p>
                        <a href="{{ route('shop') }}" class="text-charcoal underline">Continue Shopping</a>
                    </div>
                    @endforelse
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <x-order-summary 
                        :subtotal="$subtotal" 
                        :originalSubtotal="$originalSubtotal ?? 0"
                        :shipping="$shipping"
                        :discount="$discount"
                        :total="$total"
                        :threshold="$shippingThreshold"
                        :coupon="session('coupon')"
                        :showCouponForm="true"
                    >
                        <a href="{{ route('checkout.index') }}" class="block text-center w-full btn-primary mb-4">
                            Proceed to Checkout
                        </a>
                        
                        <a href="{{ route('shop') }}" class="block text-center text-neutral-500 text-xs uppercase tracking-wider hover:text-charcoal transition-colors">
                            Continue Shopping
                        </a>
                    </x-order-summary>
                </div>
            </div>
        </div>
    </div>
@endsection
