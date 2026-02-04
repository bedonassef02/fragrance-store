@extends('layouts.app')

@section('title', 'Fawry Payment | MOON')

@section('content')
<div class="bg-cream pt-32 pb-24 min-h-screen">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="text-center mb-12">
            <div class="mb-6 flex justify-center animate-fadeInUp">
                 <div class="w-20 h-20 rounded-full bg-accent/10 flex items-center justify-center border-2 border-accent">
                     <svg class="w-10 h-10 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                     </svg>
                 </div>
            </div>
            <h1 class="text-3xl md:text-4xl font-serif text-charcoal mb-4 animate-fadeInUp" style="animation-delay: 0.1s">Pay with Fawry</h1>
            <p class="text-neutral-600 animate-fadeInUp" style="animation-delay: 0.2s">Complete your payment at any Fawry outlet</p>
        </div>

        <!-- Reference Code Card -->
        <div class="bg-neutral-50 border border-neutral-200 p-8 text-center mb-8 animate-fadeInUp" style="animation-delay: 0.3s">
            <p class="text-sm text-neutral-500 uppercase tracking-widest mb-3">Your Reference Code</p>
            <div class="bg-white border-2 border-charcoal px-8 py-6 mb-4 inline-block">
                <span class="text-3xl md:text-4xl font-mono font-bold text-charcoal tracking-widest">{{ $referenceCode }}</span>
            </div>
            <div class="flex justify-center gap-4 mt-4">
                <button onclick="navigator.clipboard.writeText('{{ $referenceCode }}')" 
                        class="text-sm text-accent hover:text-charcoal transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Copy Code
                </button>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="bg-neutral-50 border border-neutral-200 p-8 mb-8 animate-fadeInUp" style="animation-delay: 0.4s">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-neutral-200">
                <span class="text-neutral-600">Order Number</span>
                <span class="text-charcoal font-medium">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-neutral-200">
                <span class="text-neutral-600">Amount to Pay</span>
                <span class="text-2xl font-serif text-charcoal">{{ number_format($totalAmount) }} LE</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-neutral-600">Expires</span>
                <span class="text-accent font-medium">{{ $expiresAt->format('M j, Y g:i A') }}</span>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-amber-50 border border-amber-200 p-6 mb-8 animate-fadeInUp" style="animation-delay: 0.5s">
            <h3 class="font-medium text-charcoal mb-4">How to Pay:</h3>
            <ol class="space-y-3 text-neutral-600">
                <li class="flex gap-3">
                    <span class="w-6 h-6 bg-accent text-white rounded-full flex items-center justify-center text-sm flex-shrink-0">1</span>
                    <span>Go to any <strong>Fawry outlet</strong> (pharmacies, supermarkets, mobile shops)</span>
                </li>
                <li class="flex gap-3">
                    <span class="w-6 h-6 bg-accent text-white rounded-full flex items-center justify-center text-sm flex-shrink-0">2</span>
                    <span>Tell them you want to pay a <strong>Fawry bill</strong></span>
                </li>
                <li class="flex gap-3">
                    <span class="w-6 h-6 bg-accent text-white rounded-full flex items-center justify-center text-sm flex-shrink-0">3</span>
                    <span>Provide the reference code: <strong class="font-mono">{{ $referenceCode }}</strong></span>
                </li>
                <li class="flex gap-3">
                    <span class="w-6 h-6 bg-accent text-white rounded-full flex items-center justify-center text-sm flex-shrink-0">4</span>
                    <span>Pay <strong>{{ number_format($totalAmount) }} LE</strong> and keep your receipt</span>
                </li>
            </ol>
        </div>

        <!-- Alternative: Fawry App -->
        <div class="text-center text-neutral-500 text-sm mb-8 animate-fadeInUp" style="animation-delay: 0.6s">
            <p>Or pay via the <strong>Fawry App</strong> using the same reference code</p>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fadeInUp" style="animation-delay: 0.7s">
            <a href="{{ route('orders.show', $order->order_number) }}" class="btn-primary text-center">
                View Order
            </a>
            <a href="{{ route('shop') }}" class="btn-secondary text-center">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection
