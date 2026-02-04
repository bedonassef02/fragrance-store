@extends('layouts.app')

@section('title', 'Payment Failed | MOON')

@section('content')
<div class="bg-cream pt-32 pb-24 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-2xl text-center">
        <div class="mb-8 flex justify-center animate-fadeInUp">
             <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center border-2 border-red-400">
                 <svg class="w-10 h-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                 </svg>
             </div>
        </div>
        
        <h1 class="text-4xl md:text-5xl font-serif text-charcoal mb-6 animate-fadeInUp" style="animation-delay: 0.1s">Payment Failed</h1>
        <p class="text-lg text-neutral-600 mb-2 animate-fadeInUp" style="animation-delay: 0.2s">We couldn't process your payment.</p>
        
        @if(session('error'))
        <p class="text-red-500 mb-6 animate-fadeInUp" style="animation-delay: 0.2s">{{ session('error') }}</p>
        @endif

        <div class="bg-neutral-50 p-8 border border-neutral-200 mb-12 animate-fadeInUp" style="animation-delay: 0.3s">
            <p class="text-neutral-600 mb-4">Your order has been saved but payment was not completed.</p>
            <p class="text-neutral-600">You can try again with a different payment method or choose Cash on Delivery.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fadeInUp" style="animation-delay: 0.4s">
            <a href="{{ route('checkout.index') }}" class="btn-primary">
                Try Again
            </a>
            <a href="{{ route('shop') }}" class="btn-secondary">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection
