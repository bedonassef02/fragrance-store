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
                    <div class="space-y-4 mb-8" x-data="{ selectedPayment: 'cod' }">
                        <h2 class="text-lg font-serif text-charcoal mb-4 pb-2 border-b border-neutral-200">Payment Method</h2>
                        <div class="space-y-3">
                            @foreach($paymentMethods as $method)
                            <label class="payment-method-option cursor-pointer block">
                                <input type="radio" name="payment_method" value="{{ $method['id'] }}" 
                                       class="sr-only" 
                                       x-model="selectedPayment"
                                       {{ $loop->first ? 'checked' : '' }}>
                                <div class="border-2 bg-white p-5 flex items-center gap-4 transition-all hover:border-neutral-400"
                                     :class="selectedPayment === '{{ $method['id'] }}' ? 'border-charcoal bg-neutral-50' : 'border-neutral-200'">
                                    <div class="w-5 h-5 rounded-full border-2 flex-shrink-0 flex items-center justify-center transition-colors"
                                         :class="selectedPayment === '{{ $method['id'] }}' ? 'border-charcoal' : 'border-neutral-300'">
                                        <div class="w-2.5 h-2.5 rounded-full bg-charcoal transition-transform"
                                             :class="selectedPayment === '{{ $method['id'] }}' ? 'scale-100' : 'scale-0'"></div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-charcoal font-medium">{{ $method['name'] }}</span>
                                        <p class="text-sm text-neutral-500 mt-0.5">{{ $method['description'] }}</p>
                                    </div>
                                    @if($method['id'] === 'card')
                                    <div class="flex gap-1.5">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-5 w-auto">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-5 w-auto">
                                    </div>
                                    @elseif($method['id'] === 'wallet')
                                    <div class="text-xs text-neutral-400 text-right">
                                        <span class="block">Vodafone Cash</span>
                                        <span class="block">Orange Money</span>
                                    </div>
                                    @elseif($method['id'] === 'fawry')
                                    <div class="text-xs font-medium text-accent">
                                        Pay at 250k+ outlets
                                    </div>
                                    @endif
                                </div>
                            </label>
                            @endforeach
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
