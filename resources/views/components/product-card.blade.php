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

        @if(!$product->isOutOfStock())
        <button 
            data-id="{{ $product->id }}" 
            data-name="{{ $product->name }}"
            data-price="{{ number_format($product->price) }} LE"
            data-variants="{{ json_encode($variantsData) }}"
            class="quick-add-btn absolute bottom-0 w-full bg-moon-gold text-moon-dark py-4 font-bold uppercase text-xs tracking-widest translate-y-0 opacity-100 md:opacity-0 md:translate-y-full md:group-hover:translate-y-0 md:group-hover:opacity-100 transition-all duration-300 hover:bg-white border-t border-moon-dark/10">
            Add to Bag
        </button>
        @else
        <button disabled class="absolute bottom-0 w-full bg-gray-800 text-gray-400 py-4 font-bold uppercase text-xs tracking-widest cursor-not-allowed border-t border-white/5">
            Sold Out
        </button>
        @endif
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
