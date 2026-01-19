@extends('layouts.app')

@section('title', $product['name'] . ' | MOON')

@section('content')
    <div class="pt-32 pb-24 bg-moon-dark min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="text-sm mb-8 text-gray-500 font-light">
                <a href="{{ route('home') }}" class="hover:text-moon-gold transition-colors">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('shop') }}" class="hover:text-moon-gold transition-colors">{{ $product->category->name ?? 'Shop' }}</a>
                <span class="mx-2">/</span>
                <span class="text-gray-300">{{ $product['name'] }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Gallery -->
                <div class="space-y-4">
                    <div class="aspect-[3/4] overflow-hidden bg-gray-800 relative group">
                         <img src="{{ $product['image'] }}" 
                              class="w-full h-full object-cover cursor-zoom-in transition-transform duration-700 group-hover:scale-105" alt="{{ $product['name'] }}">
                    </div>
                </div>

                <!-- Product Info -->
                <div class="flex flex-col lg:sticky lg:top-32 h-fit">
                    <h1 class="text-3xl md:text-5xl font-serif text-white mb-6 leading-tight">{{ $product->name }}</h1>
                    
                    <div class="flex items-center space-x-4 mb-8 pb-8 border-b border-gray-800/50">
                        <span class="text-3xl text-moon-gold font-bold font-serif">{{ number_format($product->price) }} LE</span>
                        @if(isset($product->original_price) && $product->original_price)
                        <span class="text-xl text-gray-500 line-through font-light">{{ number_format($product->original_price) }} LE</span>
                        @endif
                        @if(isset($product->badge) && $product->badge)
                        <span class="text-xs font-bold {{ $product->badge_color ?? 'bg-red-600' }} text-white px-3 py-1.5 uppercase tracking-widest ml-auto">{{ $product->badge }}</span>
                        @endif
                    </div>

                    <p class="text-gray-400 leading-relaxed mb-10 font-light text-lg">
                        {{ $product->description }}
                    </p>

                    <div class="space-y-8 mb-12">
                         <!-- Size Selector -->
                        @if($product->sizes->count() > 0)
                        <div>
                            <div class="flex justify-between mb-4">
                                <label class="text-xs uppercase tracking-widest text-white font-bold">Select Size</label>
                                <a href="#" class="size-guide-trigger text-xs text-gray-500 underline hover:text-moon-gold transition-colors">Size Guide</a>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                @foreach($product->sizes as $size)
                                <button class="product-size-btn w-12 h-12 flex items-center justify-center border border-gray-700 text-gray-400 font-bold hover:border-moon-gold hover:text-white transition-all duration-300 hover:scale-110 focus:bg-moon-gold focus:text-black focus:border-moon-gold" data-size="{{ $size->size }}">{{ $size->size }}</button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Quantity & Add -->
                        <div class="flex gap-4">
                            <div class="flex items-center border border-gray-700 h-14 w-32">
                                <button id="qty-minus" class="w-10 h-full text-gray-400 hover:text-white transition-colors text-xl flex items-center justify-center">-</button>
                                <input id="quantity-input" type="number" value="1" class="flex-1 bg-transparent text-center text-white focus:outline-none appearance-none m-0 font-bold h-full">
                                <button id="qty-plus" class="w-10 h-full text-gray-400 hover:text-white transition-colors text-xl flex items-center justify-center">+</button>
                            </div>
                            <button id="add-to-bag-btn" data-id="{{ $product->id }}" class="flex-1 bg-moon-gold text-moon-dark font-bold uppercase tracking-widest hover:bg-white transition-colors h-14 text-sm shadow-[0_0_20px_rgba(198,168,124,0.2)] hover:shadow-[0_0_30px_rgba(198,168,124,0.4)]">
                                Add to Bag
                            </button>
                        </div>
                    </div>

                    <div class="space-y-5 bg-gray-800/30 p-6 border border-gray-800 rounded-sm">
                        <div class="flex items-start space-x-4 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-moon-gold mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Premium Quality Fabric designed for elegance and comfort.</span>
                        </div>
                        <div class="flex items-start space-x-4 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-moon-gold mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Dispatched within 24 hours of ordering.</span>
                        </div>
                         <div class="flex items-start space-x-4 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-moon-gold mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span>Free returns within 30 days of purchase.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
             <div class="mt-24 border-t border-gray-800 pt-16">
                <h2 class="text-2xl font-serif text-white mb-8 text-center">You May Also Like</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                    <x-product-card :product="$related" />
                    @endforeach
                </div>
            </div>
@endsection
