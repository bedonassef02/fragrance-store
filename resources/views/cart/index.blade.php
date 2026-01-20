@extends('layouts.app')

@section('title', 'Shopping Cart | MOON')

@section('content')
    <div class="pt-44 pb-24 bg-moon-dark min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-serif text-white mb-12 text-center">Shopping Bag</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 cart-container">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-8" id="cart-items">
                    @forelse($cartItems as $variantId => $item)
                    <!-- Item -->
                    <div class="cart-item-row flex gap-6 border-b border-gray-800 pb-8" id="row-{{ $variantId }}">
                        <a href="{{ route('product.show', $item['slug']) }}" class="w-32 h-40 bg-gray-800 flex-shrink-0 block">
                            <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                        </a>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start">
                                    <a href="{{ route('product.show', $item['slug']) }}">
                                        <h3 class="text-white font-serif text-lg hover:text-moon-gold transition-colors">{{ $item['name'] }}</h3>
                                    </a>
                                    <span class="text-moon-gold font-bold">{{ number_format($item['price']) }} LE</span>
                                    @if(isset($item['original_price']) && $item['original_price'] > $item['price'])
                                        <span class="text-gray-500 line-through text-sm ml-2">{{ number_format($item['original_price']) }} LE</span>
                                    @endif
                                </div>
                                <p class="text-gray-500 text-sm mt-1">
                                    Size: {{ $item['size'] }} 
                                    @if(isset($item['color']) && $item['color'])
                                    <span class="mx-2">|</span> {{ $item['color'] }}
                                    @endif
                                </p>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div class="flex items-center border border-gray-700">
                                    <button class="cart-qty-btn px-3 py-1 text-gray-400 hover:text-white" data-action="decrease" data-id="{{ $variantId }}">-</button>
                                    <span class="cart-qty-display px-2 text-white text-sm" id="qty-{{ $variantId }}">{{ $item['quantity'] }}</span>
                                    <button class="cart-qty-btn px-3 py-1 text-gray-400 hover:text-white" data-action="increase" data-id="{{ $variantId }}">+</button>
                                </div>
                                <button class="cart-remove-btn text-gray-500 text-xs uppercase tracking-widest hover:text-red-500 transition-colors" data-id="{{ $variantId }}">Remove</button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <p class="text-gray-400 mb-4">Your cart is empty.</p>
                        <a href="{{ route('shop') }}" class="text-moon-gold underline">Continue Shopping</a>
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
                        <a href="{{ route('checkout.index') }}" class="block text-center w-full bg-moon-gold text-moon-dark font-bold uppercase tracking-widest py-4 hover:bg-white transition-colors mb-4">
                            Proceed to Checkout
                        </a>
                        
                        <a href="{{ route('shop') }}" class="block text-center text-gray-400 text-xs uppercase tracking-widest hover:text-moon-gold transition-colors">
                            Continue Shopping
                        </a>
                    </x-order-summary>
                </div>
@endsection
