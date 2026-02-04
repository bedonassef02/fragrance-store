@props(['product'])

<div class="group relative fade-in">
    <div class="relative overflow-hidden aspect-[4/5] mb-4 bg-gray-800">
        <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full">
            <img src="{{ $product->image }}" 
                 loading="lazy"
                 class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110" alt="{{ $product->name }}">
        </a>
        
        @if($product->isOutOfStock())
            <span class="absolute top-4 left-4 bg-gray-500 text-white text-[10px] font-bold px-3 py-1.5 uppercase tracking-widest">Sold Out</span>
        @elseif($product->badge)
            <span class="absolute top-4 left-4 {{ $product->badge_color ?? 'bg-moon-gold' }} text-white text-[10px] font-bold px-3 py-1.5 uppercase tracking-widest">{{ $product->badge }}</span>
        @endif

        @php
            // We pass full variants data based on capacity for "Smart Popup" logic in quick-add.js
            $variantsData = $product->variants->map(fn($v) => [
                'id' => $v->id,
                'capacity' => $v->capacity . ' ' . $v->unit, // Perfume capacity
                'price' => number_format($v->price) . ' LE',
                'qty' => $v->quantity
            ]);
        @endphp

        <div class="absolute bottom-0 left-0 w-full flex z-20">
            @if(!$product->isOutOfStock())
            <button 
                data-id="{{ $product->id }}" 
                data-name="{{ $product->name }}"
                data-price="{{ number_format($product->price) }} LE"
                data-variants='{{ json_encode($variantsData) }}'
                class="quick-add-btn flex-1 bg-moon-gold text-moon-dark py-3 font-bold uppercase text-[10px] tracking-widest hover:bg-white transition-colors border-t border-moon-dark/10 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span class="hidden sm:inline">Add</span>
            </button>
            @else
            <button disabled class="flex-1 bg-gray-800 text-gray-400 py-3 font-bold uppercase text-[10px] tracking-widest cursor-not-allowed border-t border-white/5 flex items-center justify-center gap-2">
                <span>Sold Out</span>
            </button>
            @endif

            <button 
                type="button" 
                class="wishlist-toggle w-12 bg-black/80 text-white hover:text-moon-gold transition-colors flex items-center justify-center border-l border-white/10"
                data-id="{{ $product->id }}"
                aria-label="Add to Wishlist">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>
        </div>
    </div>
    <div class="text-center group-hover:-translate-y-1 transition-transform duration-300">
        @if($product->brand)
        <a href="{{ route('shop', ['brand' => $product->brand_id]) }}" class="text-xs text-gray-500 uppercase tracking-widest mb-1 hover:text-moon-gold transition-colors block">
            {{ $product->brand->name }}
        </a>
        @endif
        <a href="{{ route('product.show', $product->slug) }}" class="block">
            <h3 class="text-lg font-serif mb-2 text-white group-hover:text-moon-gold transition-colors cursor-pointer">{{ $product->name }}</h3>
        </a>
        
        @if($product->concentration)
            <p class="text-[10px] text-moon-gold/80 uppercase tracking-widest mb-1">{{ $product->concentration }}</p>
        @endif

        <p class="text-sm font-bold text-gray-400">
            @if($product->original_price)
            <span class="line-through text-gray-600 mr-2 font-normal">{{ number_format($product->original_price) }} LE</span>
            @endif
            <span class="text-moon-gold">{{ number_format($product->price) }} LE</span>
        </p>
    </div>
</div>
