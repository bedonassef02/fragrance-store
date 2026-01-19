@extends('layouts.app')

@section('title', 'Shopping Cart | MOON')

@section('content')
    <div class="pt-32 pb-24 bg-moon-dark min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-serif text-white mb-12 text-center">Shopping Bag</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-8">
                    @forelse($cartItems as $item)
                    <!-- Item -->
                    <div class="cart-item-row flex gap-6 border-b border-gray-800 pb-8" id="row-{{ $item['key'] }}">
                        <div class="w-32 h-40 bg-gray-800 flex-shrink-0">
                            <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-white font-serif text-lg">{{ $item['name'] }}</h3>
                                    <span class="text-moon-gold font-bold">{{ number_format($item['price']) }} LE</span>
                                </div>
                                <p class="text-gray-500 text-sm mt-1">Size: {{ $item['size'] }}</p>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div class="flex items-center border border-gray-700">
                                    <button class="cart-qty-btn px-3 py-1 text-gray-400 hover:text-white" data-action="decrease" data-key="{{ $item['key'] }}">-</button>
                                    <span class="cart-qty-display px-2 text-white text-sm" id="qty-{{ $item['key'] }}">{{ $item['quantity'] }}</span>
                                    <button class="cart-qty-btn px-3 py-1 text-gray-400 hover:text-white" data-action="increase" data-key="{{ $item['key'] }}">+</button>
                                </div>
                                <button class="cart-remove-btn text-gray-500 text-xs uppercase tracking-widest hover:text-red-500 transition-colors" data-key="{{ $item['key'] }}">Remove</button>
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
                    <div class="bg-white/5 p-8 border border-white/10 sticky top-32">
                        <h3 class="text-white font-serif text-xl mb-6">Order Summary</h3>
                        
                        <div class="space-y-4 mb-8 text-sm text-gray-400">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="text-white" id="cart-subtotal">{{ number_format($subtotal) }} LE</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping</span>
                                <span class="text-white">{{ number_format($shipping) }} LE</span>
                            </div>
                            <!-- Discount functionality -->
                            @if(isset($discount) && $discount > 0)
                            <div class="flex justify-between text-moon-gold">
                                <span>Discount</span>
                                <span id="cart-discount">-{{ number_format($discount) }} LE</span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Coupon Input -->
                        <div class="mb-6 pt-4 border-t border-gray-800">
                             @if(session('coupon'))
                                <div class="flex justify-between items-center bg-green-900/30 border border-green-800 p-3 rounded">
                                    <span class="text-green-400 text-sm font-mono">{{ session('coupon.code') }}</span>
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

                        <div class="border-t border-gray-700 pt-6 mb-8">
                            <div class="flex justify-between items-end">
                                <span class="text-white font-serif text-lg">Total</span>
                                <span class="text-2xl font-bold text-white" id="cart-total">{{ number_format($total) }} LE</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-right">Including VAT</p>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="block text-center w-full bg-moon-gold text-moon-dark font-bold uppercase tracking-widest py-4 hover:bg-white transition-colors mb-4">
                            Proceed to Checkout
                        </a>
                        
                        <a href="{{ route('shop') }}" class="block text-center text-gray-400 text-xs uppercase tracking-widest hover:text-moon-gold transition-colors">
                            Continue Shopping
                        </a>
                    </div>
                </div>
@endsection
