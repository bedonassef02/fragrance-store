@extends('layouts.app')

@section('title', 'Checkout | MOON')

@section('content')
<div class="bg-moon-dark pt-44 pb-24 min-h-screen">
    <div class="container mx-auto px-4 max-w-6xl">
        <h1 class="text-4xl font-serif text-white mb-12 text-center fade-in">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 slide-up">
            <!-- Form -->
            <div class="lg:col-span-7 space-y-8">
                @if(session('error'))
                <div class="bg-red-500/10 border border-red-500 text-red-500 p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-500/10 border border-red-500 text-red-500 p-4 rounded mb-4">
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
                        <h2 class="text-xl font-serif text-white mb-4 border-b border-gray-800 pb-2">Contact Information</h2>
                        <input type="email" name="email" placeholder="Email Address" required 
                               class="w-full bg-gray-900 border border-gray-700 text-white p-4 focus:border-moon-gold focus:ring-0 outline-none transition-colors rounded-sm placeholder-gray-500"
                               value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}">
                    </div>

                    <!-- Shipping -->
                    <div class="space-y-4 mb-10">
                        <h2 class="text-xl font-serif text-white mb-4 border-b border-gray-800 pb-2">Shipping Address</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="first_name" placeholder="First Name" required 
                                   class="w-full bg-gray-900 border border-gray-700 text-white p-4 focus:border-moon-gold focus:ring-0 outline-none transition-colors rounded-sm placeholder-gray-500"
                                   value="{{ old('first_name', auth()->check() ? explode(' ', auth()->user()->name)[0] : '') }}">
                            <input type="text" name="last_name" placeholder="Last Name" required 
                                   class="w-full bg-gray-900 border border-gray-700 text-white p-4 focus:border-moon-gold focus:ring-0 outline-none transition-colors rounded-sm placeholder-gray-500"
                                   value="{{ old('last_name') }}">
                        </div>
                        <input type="text" name="address" placeholder="Address" required 
                               class="w-full bg-gray-900 border border-gray-700 text-white p-4 focus:border-moon-gold focus:ring-0 outline-none transition-colors rounded-sm placeholder-gray-500"
                               value="{{ old('address') }}">
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="city" placeholder="City" required 
                                   class="w-full bg-gray-900 border border-gray-700 text-white p-4 focus:border-moon-gold focus:ring-0 outline-none transition-colors rounded-sm placeholder-gray-500"
                                   value="{{ old('city') }}">
                            <input type="text" name="phone" placeholder="Phone Number" required 
                                   class="w-full bg-gray-900 border border-gray-700 text-white p-4 focus:border-moon-gold focus:ring-0 outline-none transition-colors rounded-sm placeholder-gray-500"
                                   value="{{ old('phone') }}">
                        </div>
                    </div>

                    <!-- Payment -->
                     <div class="space-y-4 mb-8">
                        <h2 class="text-xl font-serif text-white mb-4 border-b border-gray-800 pb-2">Payment Method</h2>
                        <div class="border border-moon-gold/30 bg-moon-gold/5 p-5 rounded-sm flex items-center gap-4 cursor-pointer">
                            <div class="w-5 h-5 rounded-full border-4 border-moon-gold bg-transparent"></div>
                            <span class="text-white font-bold tracking-wide">Cash on Delivery (COD)</span>
                        </div>
                        <p class="text-sm text-gray-500 ml-9">Pay comfortably when your order is delivered to your doorstep.</p>
                    </div>
                </form>
            </div>

            <!-- Summary -->
            <div class="lg:col-span-5">
                <div class="bg-gray-800/20 p-8 sticky top-32 border border-gray-800 backdrop-blur-sm">
                    <h2 class="text-xl font-serif text-white mb-6 border-b border-gray-700 pb-4">Order Summary</h2>
                    <div class="space-y-6 mb-8 max-h-96 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">
                        @foreach($cart as $item)
                        <div class="flex gap-4 items-start">
                             <div class="w-16 h-20 bg-gray-700 flex-shrink-0 overflow-hidden relative border border-gray-700">
                                 <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                                 <span class="absolute top-0 right-0 bg-moon-gold text-black text-[10px] font-bold px-1">{{ $item['quantity'] }}</span>
                             </div>
                             <div class="flex-1 min-w-0">
                                 <h3 class="text-white font-bold truncate">{{ $item['name'] }}</h3>
                                 <p class="text-sm text-gray-400">Size: {{ $item['size'] }}</p>
                                 <p class="text-moon-gold mt-1">{{ number_format($item['price'] * $item['quantity']) }} LE</p>
                             </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="border-t border-gray-700 pt-4 space-y-3 text-sm">
                        <div class="flex justify-between text-gray-400">
                            <span>Subtotal</span>
                            <span>{{ number_format($subtotal) }} LE</span>
                        </div>
                         <div class="flex justify-between text-gray-400">
                            <span>Shipping</span>
                            <span>{{ $shipping > 0 ? number_format($shipping) . ' LE' : 'Free' }}</span>
                        </div>
                        @if(isset($discount) && $discount > 0)
                        <div class="flex justify-between text-moon-gold">
                            <span>Discount</span>
                            <span>-{{ number_format($discount) }} LE</span>
                        </div>
                        @endif
                         <div class="flex justify-between text-white text-xl font-serif font-bold pt-4 border-t border-gray-700 mt-2">
                            <span>Total</span>
                            <span>{{ number_format($total) }} LE</span>
                        </div>
                    </div>

                    <button type="submit" form="checkout-form" class="w-full mt-8 bg-moon-gold text-moon-dark font-bold uppercase tracking-widest py-4 hover:bg-white transition-all duration-300 shadow-[0_0_20px_rgba(198,168,124,0.2)] hover:shadow-[0_0_30px_rgba(198,168,124,0.4)]">
                        Complete Order
                    </button>
                    
                    <div class="mt-6 flex justify-center">
                        <a href="{{ route('cart') }}" class="text-sm text-gray-500 underline hover:text-moon-gold transition-colors">Return to Cart</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
