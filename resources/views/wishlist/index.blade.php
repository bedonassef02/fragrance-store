@extends('layouts.app')

@section('title', 'My Wishlist | MOON')

@section('content')
<div class="pt-28 pb-24 bg-cream min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-accent uppercase tracking-[0.3em] text-xs font-medium mb-3">Your Favorites</p>
            <h1 class="text-3xl md:text-4xl font-serif text-charcoal mb-4">Wishlist</h1>
            <p class="text-neutral-500 max-w-md mx-auto">Your personal collection of must-have fragrances.</p>
        </div>

        @if($products->isEmpty())
        <div class="text-center py-20 border border-neutral-200 bg-neutral-50 max-w-xl mx-auto">
            <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <h2 class="text-xl font-serif text-charcoal mb-3">Your wishlist is empty</h2>
            <p class="text-neutral-500 mb-6">Start adding your favorite scents.</p>
            <a href="{{ route('shop') }}" class="btn-primary">Explore Fragrances</a>
        </div>
        @else
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
