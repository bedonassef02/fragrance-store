@props(['product'])

<div class="group relative fade-in">
    <div class="relative overflow-hidden aspect-[4/5] mb-4 bg-gray-800">
        <a href="{{ route('product.show', $product['slug']) }}" class="block w-full h-full">
            <img src="{{ $product['image'] }}" 
                 loading="lazy"
                 class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110" alt="{{ $product['name'] }}">
        </a>
        
        @if(isset($product['badge']) && $product['badge'])
        <span class="absolute top-4 left-4 {{ $product['badge_color'] ?? 'bg-moon-gold' }} text-white text-[10px] font-bold px-3 py-1.5 uppercase tracking-widest">{{ $product['badge'] }}</span>
        @endif

        @php
            $hasMultipleColors = $product->variants->pluck('color_id')->unique()->count() > 1;
            $firstColor = $product->variants->first()?->color;
        @endphp

        @if($hasMultipleColors)
        <a href="{{ route('product.show', $product['slug']) }}" 
            class="absolute bottom-0 w-full bg-moon-gold text-moon-dark py-4 font-bold uppercase text-xs tracking-widest translate-y-full group-hover:translate-y-0 transition-transform duration-300 hover:bg-white border-t border-moon-dark/10 flex items-center justify-center">
            Select Option
        </a>
        @else
        <button 
            data-id="{{ $product['id'] }}" 
            data-name="{{ $product['name'] }}"
            data-price="{{ number_format($product['price']) }} LE"
            data-sizes="{{ $product->variants->pluck('size')->unique()->implode(',') }}"
            data-color="{{ $firstColor?->name }}"
            class="quick-add-btn absolute bottom-0 w-full bg-moon-gold text-moon-dark py-4 font-bold uppercase text-xs tracking-widest translate-y-full group-hover:translate-y-0 transition-transform duration-300 hover:bg-white border-t border-moon-dark/10">
            Add to Bag
        </button>
        @endif
    </div>
    <div class="text-center group-hover:-translate-y-1 transition-transform duration-300">
        <a href="{{ route('product.show', $product['slug']) }}" class="block">
            <h3 class="text-lg font-serif mb-2 text-white group-hover:text-moon-gold transition-colors cursor-pointer">{{ $product['name'] }}</h3>
        </a>
        <p class="text-sm font-bold text-gray-400">
            @if(isset($product['original_price']) && $product['original_price'])
            <span class="line-through text-gray-600 mr-2 font-normal">{{ number_format($product['original_price']) }} LE</span>
            @endif
            <span class="text-moon-gold">{{ number_format($product['price']) }} LE</span>
        </p>
    </div>
</div>
