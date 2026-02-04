@extends('layouts.app')

@section('title', 'Order Confirmed | MOON')

@section('content')
<div class="bg-cream pt-32 pb-24 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-2xl text-center">
        <div class="mb-8 flex justify-center animate-fadeInUp">
             <div class="w-20 h-20 rounded-full bg-accent/10 flex items-center justify-center border-2 border-accent">
                 <svg class="w-10 h-10 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                 </svg>
             </div>
        </div>
        
        <h1 class="text-4xl md:text-5xl font-serif text-charcoal mb-6 animate-fadeInUp" style="animation-delay: 0.1s">Thank You</h1>
        <p class="text-lg text-neutral-600 mb-2 animate-fadeInUp" style="animation-delay: 0.2s">Your order has been placed successfully.</p>
        <div class="mb-12 animate-fadeInUp" style="animation-delay: 0.2s">
            <a href="{{ route('orders.show', $orderNumber) }}" class="text-accent font-medium text-lg hover:text-charcoal transition-colors border-b-2 border-accent hover:border-charcoal pb-1">Order #{{ $orderNumber }}</a>
        </div>
        
        <div class="bg-neutral-50 p-8 border border-neutral-200 mb-12 animate-fadeInUp" style="animation-delay: 0.3s">
            <p class="text-neutral-600 mb-4">We have received your order and sent a confirmation email to your inbox.</p>
            <p class="text-neutral-600">Our team will contact you shortly to confirm delivery details.</p>
        </div>

        <a href="{{ route('shop') }}" class="btn-primary inline-block animate-fadeInUp" style="animation-delay: 0.4s">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
