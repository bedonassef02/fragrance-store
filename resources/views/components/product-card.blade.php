@props(['product'])

<div class="group relative">
    <div class="relative overflow-hidden aspect-[3/4] mb-4 bg-neutral-100">
        <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full">
            <img src="{{ $product->image }}" 
                 srcset="{{ $product->srcset }}"
                 sizes="(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 25vw"
                 loading="lazy"
                 class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105" 
                 alt="{{ $product->name }}">
        </a>
        
        <!-- Badges -->
        @if($product->isOutOfStock())
            <x-shop.ui.badge label="Sold Out" color="neutral" />
        @elseif($product->badge)
            <x-shop.ui.badge :label="$product->badge" />
        @endif

        <!-- Wishlist Button -->
        <button 
            type="button" 
            class="wishlist-toggle absolute top-3 right-3 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-neutral-500 hover:text-accent transition-colors shadow-sm"
            data-id="{{ $product->id }}"
            aria-label="Add to Wishlist">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>

        @php
            $variantsData = $product->variants->map(fn($v) => [
                'id' => $v->id,
                'capacity' => $v->capacity . ' ' . $v->unit,
                'price' => number_format($v->price) . ' LE',
                'qty' => $v->quantity
            ]);
        @endphp

        <!-- Quick Add Button -->
        @if(!$product->isOutOfStock())
        <button 
            data-id="{{ $product->id }}" 
            data-name="{{ $product->name }}"
            data-price="{{ number_format($product->display_price) }} LE"
            data-variants='{{ json_encode($variantsData) }}'
            class="quick-add-btn absolute bottom-0 left-0 right-0 bg-charcoal/95 text-cream py-3.5 text-[10px] font-medium uppercase tracking-[0.15em] opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 hover:bg-charcoal">
            Add to Bag
        </button>
        @else
        <div class="absolute bottom-0 left-0 right-0 bg-neutral-200 text-neutral-500 py-3.5 text-[10px] font-medium uppercase tracking-[0.15em] text-center">
            Sold Out
        </div>
        @endif
    </div>
    
    <!-- Product Info -->
    <div class="text-center">
        @if($product->brand)
        <a href="{{ route('shop', ['brand' => $product->brand_id]) }}" class="text-[10px] text-neutral-500 uppercase tracking-[0.15em] mb-1.5 hover:text-charcoal transition-colors block">
            {{ $product->brand->name }}
        </a>
        @endif
        
        <a href="{{ route('product.show', $product->slug) }}" class="block">
            <h3 class="text-base font-serif text-charcoal mb-1.5 group-hover:text-accent transition-colors">{{ $product->name }}</h3>
        </a>
        
        @if($product->concentration)
            <p class="text-[10px] text-neutral-500 uppercase tracking-[0.1em] mb-2">{{ $product->concentration }}</p>
        @endif

        <p class="text-sm">
            @if($product->original_price)
            <span class="line-through text-neutral-400 mr-1.5">{{ number_format($product->original_price) }} LE</span>
            @endif
            <span class="text-charcoal font-medium">{{ number_format($product->display_price) }} LE</span>
        </p>
    </div>
</div>
