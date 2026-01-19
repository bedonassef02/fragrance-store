@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' | MOON')

@section('content')
<div class="bg-moon-dark pt-44 pb-24 min-h-screen">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 fade-in">
            <div>
                <h1 class="text-3xl font-serif text-white mb-2">Order Details</h1>
                <p class="text-moon-gold font-bold text-xl">{{ $order->order_number }}</p>
                <p class="text-gray-400 text-sm mt-1">Placed on {{ $order->created_at->format('F j, Y') }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="inline-block px-4 py-2 border border-moon-gold text-moon-gold uppercase tracking-widest text-xs font-bold">
                    {{ $order->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 slide-up">
            <!-- Items -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-gray-800/20 border border-gray-800 p-8 rounded-sm">
                    <h2 class="text-xl font-serif text-white mb-6 border-b border-gray-700 pb-4">Items</h2>
                    <div class="space-y-6">
                        @foreach($order->items as $item)
                        <div class="flex gap-6 items-center">
                             <div class="w-20 h-24 bg-gray-700 flex-shrink-0 overflow-hidden border border-gray-700">
                                 <img src="{{ $item->product->image }}" class="w-full h-full object-cover">
                             </div>
                             <div class="flex-1">
                                 <h3 class="text-white font-bold">{{ $item->product->name }}</h3>
                                 <p class="text-sm text-gray-400">
                                     Size: {{ $item->size }} 
                                     @if($item->color) | Color: {{ $item->color }} @endif
                                     | Qty: {{ $item->quantity }}
                                 </p>
                             </div>
                             <div class="text-right">
                                 <p class="text-moon-gold">{{ number_format($item->price * $item->quantity) }} LE</p>
                             </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Totals -->
                <div class="bg-gray-800/20 border border-gray-800 p-8 rounded-sm">
                    <h2 class="text-xl font-serif text-white mb-6 border-b border-gray-700 pb-4">Summary</h2>
                    <div class="space-y-3 text-sm text-gray-400">
                         <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>{{ number_format($order->subtotal ?? ($order->total_amount + ($order->discount_amount ?? 0) - 150)) }} LE</span>
                        </div>
                        @php
                            $calculatedSubtotal = $order->subtotal ?? ($order->total_amount + ($order->discount_amount ?? 0) - 150);
                            $calculatedShipping = $order->total_amount - $calculatedSubtotal + ($order->discount_amount ?? 0);
                        @endphp
                         <div class="flex justify-between">
                            <span>Shipping</span>
                            <span>{{ $calculatedShipping > 0 ? number_format($calculatedShipping) . ' LE' : 'Free' }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between text-moon-gold">
                            <span>Discount <small class="text-gray-500">({{ $order->coupon_code }})</small></span>
                            <span>-{{ number_format($order->discount_amount) }} LE</span>
                        </div>
                        @endif
                         <div class="flex justify-between text-white text-lg font-bold pt-4 border-t border-gray-700 mt-2">
                            <span>Total</span>
                            <span>{{ number_format($order->total_amount) }} LE</span>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="bg-gray-800/20 border border-gray-800 p-8 rounded-sm">
                    <h2 class="text-xl font-serif text-white mb-6 border-b border-gray-700 pb-4">Shipping Info</h2>
                    <div class="text-gray-400 space-y-2 text-sm">
                        <p class="text-white font-bold">{{ $order->first_name }} {{ $order->last_name }}</p>
                        <p>{{ $order->address }}</p>
                        <p>{{ $order->city }}</p>
                        <p>{{ $order->phone }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center slide-up delay-200">
             <a href="{{ route('shop') }}" class="text-sm text-gray-500 underline hover:text-moon-gold transition-colors">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection
