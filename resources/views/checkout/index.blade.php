@extends('layouts.app')

@section('title', 'Checkout | MOON')

@section('content')
<div class="bg-cream pt-28 pb-24 min-h-screen">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="text-center mb-12">
            <p class="text-accent uppercase tracking-[0.3em] text-xs font-medium mb-3">Final Step</p>
            <h1 class="text-3xl md:text-4xl font-serif text-charcoal">Checkout</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Form -->
            <div class="lg:col-span-7 space-y-8">
                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 p-4 text-sm">
                    {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 p-4 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <!-- Contact -->
                    <div class="space-y-4 mb-10">
                        <h2 class="text-lg font-serif text-charcoal mb-4 pb-2 border-b border-neutral-200">Contact Information</h2>
                        <input type="email" name="email" placeholder="Email Address" required 
                               value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}"
                               class="w-full border border-neutral-300 px-4 py-3 text-charcoal placeholder-neutral-400 focus:border-charcoal focus:outline-none transition-colors">
                    </div>

                    <!-- Shipping -->
                    <div class="space-y-4 mb-10">
                        <h2 class="text-lg font-serif text-charcoal mb-4 pb-2 border-b border-neutral-200">Shipping Address</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="first_name" placeholder="First Name" required 
                                   value="{{ old('first_name', auth()->check() ? explode(' ', auth()->user()->name)[0] : '') }}"
                                   class="w-full border border-neutral-300 px-4 py-3 text-charcoal placeholder-neutral-400 focus:border-charcoal focus:outline-none transition-colors">
                            <input type="text" name="last_name" placeholder="Last Name" required 
                                   value="{{ old('last_name') }}"
                                   class="w-full border border-neutral-300 px-4 py-3 text-charcoal placeholder-neutral-400 focus:border-charcoal focus:outline-none transition-colors">
                        </div>
                        <input type="text" name="address" placeholder="Address" required 
                               value="{{ old('address') }}"
                               class="w-full border border-neutral-300 px-4 py-3 text-charcoal placeholder-neutral-400 focus:border-charcoal focus:outline-none transition-colors">
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="city" placeholder="City" required 
                                   value="{{ old('city') }}"
                                   class="w-full border border-neutral-300 px-4 py-3 text-charcoal placeholder-neutral-400 focus:border-charcoal focus:outline-none transition-colors">
                            <div class="relative">
                                <input type="text" name="phone" placeholder="Phone Number (e.g. 01xxxxxxxxx)" required 
                                       value="{{ old('phone') }}"
                                       class="w-full border border-neutral-300 px-4 py-3 text-charcoal placeholder-neutral-400 focus:border-charcoal focus:outline-none transition-colors">
                            </div>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="space-y-4 mb-8">
                        <h2 class="text-lg font-serif text-charcoal mb-4 pb-2 border-b border-neutral-200">Payment Method</h2>
                        <div class="border-2 border-charcoal bg-neutral-50 p-5 flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-4 border-charcoal bg-transparent flex-shrink-0"></div>
                            <div>
                                <span class="text-charcoal font-medium">Cash on Delivery (COD)</span>
                                <p class="text-sm text-neutral-500 mt-1">Pay when your order is delivered.</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Summary -->
            <div class="lg:col-span-5">
                <div class="bg-neutral-50 p-8 sticky top-28 border border-neutral-200">
                    <h2 class="text-lg font-serif text-charcoal mb-6 pb-4 border-b border-neutral-200">Order Review</h2>
                    <div class="space-y-5 mb-8 max-h-80 overflow-y-auto pr-2">
                        @foreach($cart as $item)
                        <div class="flex gap-4 items-start">
                            <div class="w-16 h-20 bg-neutral-100 flex-shrink-0 overflow-hidden relative border border-neutral-200">
                                <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                                <span class="absolute top-0 right-0 bg-charcoal text-cream text-[10px] font-medium px-1.5 py-0.5">{{ $item['quantity'] }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-charcoal font-medium truncate">{{ $item['name'] }}</h3>
                                <p class="text-sm text-neutral-500">
                                    @if(isset($item['concentration']) && $item['concentration'])
                                    {{ $item['concentration'] }} · 
                                    @endif
                                    {{ $item['capacity'] }}
                                </p>
                                <p class="text-charcoal mt-1 font-medium">{{ number_format($item['price'] * $item['quantity']) }} LE</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <x-order-summary :subtotal="$subtotal" :shipping="$shipping" :discount="$discount" :total="$total">
                        <button type="submit" form="checkout-form" class="w-full mt-6 btn-primary">
                            Complete Order
                        </button>
                    
                        <div class="mt-4 text-center">
                            <a href="{{ route('cart.index') }}" class="text-sm text-neutral-500 underline hover:text-charcoal transition-colors">Return to Cart</a>
                        </div>
                    </x-order-summary>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
