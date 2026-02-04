@extends('layouts.app')

@section('title', 'Processing Payment | MOON')

@section('content')
<div class="bg-cream pt-32 pb-24 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-2xl text-center">
        <div class="mb-8 flex justify-center animate-fadeInUp">
             <div class="w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center border-2 border-amber-400 animate-pulse">
                 <svg class="w-10 h-10 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                 </svg>
             </div>
        </div>
        
        <h1 class="text-4xl md:text-5xl font-serif text-charcoal mb-6 animate-fadeInUp" style="animation-delay: 0.1s">Processing Payment</h1>
        <p class="text-lg text-neutral-600 mb-2 animate-fadeInUp" style="animation-delay: 0.2s">Please wait while we confirm your payment...</p>
        
        <div class="bg-neutral-50 p-8 border border-neutral-200 mb-12 animate-fadeInUp" style="animation-delay: 0.3s">
            <p class="text-neutral-600 mb-4">Order: <span class="font-medium text-charcoal">{{ $order->order_number }}</span></p>
            <p class="text-neutral-500 text-sm">This page will automatically refresh. If it doesn't update within a few minutes, please contact support.</p>
        </div>

        <div class="animate-fadeInUp" style="animation-delay: 0.4s">
            <a href="{{ route('orders.show', $order->order_number) }}" class="text-sm text-neutral-500 underline hover:text-charcoal transition-colors">
                View Order Details
            </a>
        </div>
    </div>
</div>

<script>
    // Auto-refresh to check payment status
    setTimeout(function() {
        window.location.reload();
    }, 5000);
</script>
@endsection
