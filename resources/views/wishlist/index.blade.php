@extends('layouts.app')

@section('title', 'My Wishlist | MOON')

@section('content')
<div class="pt-44 pb-24 bg-moon-dark min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl md:text-5xl font-serif text-white mb-4 text-center">My Wishlist</h1>
        <p class="text-gray-400 text-center font-light mb-16 max-w-2xl mx-auto">
            Your personal collection of favorites.
        </p>

        @if($products->isEmpty())
        <div class="text-center py-20 border border-white/5 bg-white/5 rounded-sm">
            <h2 class="text-2xl font-serif text-white mb-4">Your wishlist is empty</h2>
            <p class="text-gray-400 font-light mb-8">You haven't added any products yet.</p>
            <a href="{{ route('shop') }}" class="px-8 py-3 bg-moon-gold text-moon-dark font-bold uppercase tracking-widest text-sm hover:bg-white transition-colors">Start Shopping</a>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-12">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
