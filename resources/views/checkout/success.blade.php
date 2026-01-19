@extends('layouts.app')

@section('title', 'Order Confirmed | MOON')

@section('content')
<div class="bg-moon-dark pt-44 pb-24 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-2xl text-center">
        <div class="mb-8 flex justify-center fade-in">
             <div class="w-20 h-20 rounded-full bg-moon-gold/10 flex items-center justify-center border-2 border-moon-gold">
                 <svg class="w-10 h-10 text-moon-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                 </svg>
             </div>
        </div>
        
        <h1 class="text-4xl md:text-5xl font-serif text-white mb-6 fade-in slide-up">Thank You</h1>
        <p class="text-xl text-gray-300 mb-2 slide-up delay-100">Your order has been placed successfully.</p>
        <div class="mb-12 slide-up delay-100">
            <a href="{{ route('orders.show', $orderNumber) }}" class="text-moon-gold font-bold text-lg hover:text-white transition-colors border-b-2 border-moon-gold hover:border-white pb-1">Order #{{ $orderNumber }}</a>
        </div>
        
        <div class="bg-gray-800/30 p-8 rounded-sm border border-gray-800 mb-12 slide-up delay-200">
            <p class="text-gray-400 mb-4">We have received your order and sent a confirmation email to your inbox.</p>
            <p class="text-gray-400">Our team will contact you shortly to confirm delivery details.</p>
        </div>

        <a href="{{ route('shop') }}" class="inline-block bg-white text-moon-dark font-bold uppercase tracking-widest py-4 px-12 hover:bg-moon-gold transition-colors slide-up delay-300">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
