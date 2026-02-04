@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' | MOON')

@section('content')
<div class="bg-cream pt-32 pb-24 min-h-screen">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 animate-fadeInUp">
            <div>
                <h1 class="text-3xl font-serif text-charcoal mb-2">Order Details</h1>
                <p class="text-accent font-medium text-xl">{{ $order->order_number }}</p>
                <p class="text-neutral-500 text-sm mt-1">Placed on {{ $order->created_at->format('F j, Y') }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="inline-block px-4 py-2 border border-accent text-accent uppercase tracking-widest text-xs font-medium">
                    {{ $order->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Items -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-neutral-50 border border-neutral-200 p-8">
                    <h2 class="text-xl font-serif text-charcoal mb-6 border-b border-neutral-200 pb-4">Items</h2>
                     <div class="space-y-6">
                        @php
                            $orderOriginalSubtotal = 0;
                        @endphp
                        @foreach($order->items as $item)
                        @php
                             $currentOriginalPrice = $item->variant?->product?->original_price;
                             $hasDiscount = $currentOriginalPrice && $currentOriginalPrice > $item->unit_price;
                             if($hasDiscount) {
                                $orderOriginalSubtotal += $currentOriginalPrice * $item->quantity;
                             } else {
                                $orderOriginalSubtotal += $item->unit_price * $item->quantity;
                             }
                        @endphp
                        <div class="flex gap-6 items-center">
                             <div class="w-20 h-24 bg-neutral-100 flex-shrink-0 overflow-hidden border border-neutral-200">
                                 @php
                                     $image = $item->variant?->product?->image ?? asset('images/no-image.png');
                                 @endphp
                                 <img src="{{ $image }}" class="w-full h-full object-cover">
                             </div>
                             <div class="flex-1">
                                 <h3 class="text-charcoal font-medium">{{ $item->product_name }}</h3>
                                 <p class="text-sm text-neutral-500">
                                     Size: {{ $item->size }} 
                                     @if($item->color) | Color: {{ $item->color }} @endif
                                     | Qty: {{ $item->quantity }}
                                 </p>
                             </div>
                             <div class="text-right">
                                 <p class="text-charcoal font-medium">{{ number_format($item->total) }} LE</p>
                                 @if($hasDiscount)
                                     <p class="text-xs text-neutral-400 line-through">{{ number_format($currentOriginalPrice) }} LE</p>
                                 @else
                                     <p class="text-xs text-neutral-500">{{ number_format($item->unit_price) }} LE / unit</p>
                                 @endif
                             </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Totals -->
                <div class="bg-neutral-50 border border-neutral-200 p-8">
                    <h2 class="text-xl font-serif text-charcoal mb-6 border-b border-neutral-200 pb-4">Summary</h2>
                     <div class="space-y-3 text-sm">
                        @php
                            $actualSubtotal = $order->subtotal > 0 ? $order->subtotal : $order->items->sum('total');
                            $calculatedShipping = $order->total_amount - ($actualSubtotal - $order->discount_amount);
                        @endphp

                        @if($orderOriginalSubtotal > $actualSubtotal)
                        <div class="flex justify-between text-neutral-500">
                            <span>Value of Items</span>
                            <span class="line-through">{{ number_format($orderOriginalSubtotal) }} LE</span>
                        </div>
                        <div class="flex justify-between text-accent">
                            <span>Product Discounts</span>
                            <span>-{{ number_format($orderOriginalSubtotal - $actualSubtotal) }} LE</span>
                        </div>
                        @endif

                         <div class="flex justify-between">
                            <span class="text-neutral-600">Subtotal</span>
                            <span class="text-charcoal">{{ number_format($actualSubtotal) }} LE</span>
                        </div>
                         <div class="flex justify-between">
                            <span class="text-neutral-600">Shipping</span>
                            <span class="text-charcoal">{{ $calculatedShipping > 0 ? number_format($calculatedShipping) . ' LE' : 'Free' }}</span>
                        </div>
                        @if($order->deposit_amount > 0)
                        <div class="flex justify-between text-accent">
                             <span>Deposit Paid</span>
                             <span>-{{ number_format($order->deposit_amount) }} LE</span>
                        </div>
                        <div class="flex justify-between text-neutral-500 text-xs italic">
                             <span>Remaining Balance</span>
                             <span>{{ number_format($order->total_amount - $order->deposit_amount) }} LE</span>
                        </div>
                        @endif

                        @if($order->discount_amount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Coupon Discount <small class="text-neutral-400">({{ $order->coupon_code }})</small></span>
                            <span>-{{ number_format($order->discount_amount) }} LE</span>
                        </div>
                        @endif
                         <div class="flex justify-between text-charcoal text-lg font-serif pt-4 border-t border-neutral-200 mt-2">
                            <span>Total</span>
                            <span>{{ number_format($order->total_amount) }} LE</span>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="bg-neutral-50 border border-neutral-200 p-8">
                    <h2 class="text-xl font-serif text-charcoal mb-6 border-b border-neutral-200 pb-4">Shipping Info</h2>
                    <div class="text-neutral-600 space-y-2 text-sm">
                        <p class="text-charcoal font-medium">{{ $order->first_name }} {{ $order->last_name }}</p>
                        <p>{{ $order->address }}</p>
                        <p>{{ $order->city }}</p>
                        <p>{{ $order->phone }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center">
             <a href="{{ route('shop') }}" class="text-sm text-neutral-500 underline hover:text-accent transition-colors">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection
