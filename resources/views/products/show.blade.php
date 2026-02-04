@extends('layouts.app')

@section('title', $product->meta_title ?: $product->name . ' | MOON')
@section('description', $product->meta_description ?: 'Discover ' . $product->name . ' at Moon. ' . Str::limit(strip_tags($product->description), 100))
@section('og:title', $product->meta_title ?: $product->name . ' | MOON')
@section('og:description', $product->meta_description ?: 'Discover ' . $product->name . ' at Moon. ' . Str::limit(strip_tags($product->description), 100))
@section('og:image', $product->image)

@section('content')
    <x-seo.schema-org type="product" :data="compact('product')" />
    <x-seo.schema-org type="breadcrumb" :data="['breadcrumbs' => [
        ['name' => 'Home', 'url' => route('home')],
        ['name' => $product->category->name ?? 'Shop', 'url' => route('shop')],
        ['name' => $product->name, 'url' => route('product.show', $product->slug)],
    ]]" />
    
    <div class="pt-28 pb-24 bg-cream min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="text-sm mb-8 text-neutral-500">
                <a href="{{ route('home') }}" class="hover:text-charcoal transition-colors">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('shop') }}" class="hover:text-charcoal transition-colors">{{ $product->category->name ?? 'Shop' }}</a>
                <span class="mx-2">/</span>
                <span class="text-charcoal">{{ $product->name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
                <!-- Gallery -->
                <div class="space-y-4">
                    <div class="aspect-[3/4] overflow-hidden bg-neutral-100 relative group">
                        <img id="main-image" src="{{ $product->image }}" 
                             class="w-full h-full object-cover cursor-zoom-in transition-opacity duration-300" alt="{{ $product->name }}">
                         
                        @if($product->badge)
                        <span class="absolute top-4 left-4 bg-charcoal text-cream text-[10px] font-medium px-3 py-1.5 uppercase tracking-wider">{{ $product->badge }}</span>
                        @endif
                    </div>
                    
                    <!-- Thumbnails -->
                    @if($product->images->count() > 0)
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @if($product->image && $product->images->where('image_path', $product->getRawOriginal('image'))->isEmpty())
                            <img src="{{ $product->image }}" class="gallery-thumb w-20 h-24 object-cover border-2 border-charcoal cursor-pointer hover:opacity-80 transition-all" onclick="document.getElementById('main-image').src=this.src; document.querySelectorAll('.gallery-thumb').forEach(el => el.classList.remove('border-charcoal')); this.classList.add('border-charcoal');">
                        @endif
                        @foreach($product->images as $img)
                        <img src="{{ $img->image_path }}" 
                             class="gallery-thumb w-20 h-24 object-cover border-2 border-transparent hover:border-neutral-400 cursor-pointer hover:opacity-80 transition-all"
                             onclick="document.getElementById('main-image').src=this.src; document.querySelectorAll('.gallery-thumb').forEach(el => el.classList.remove('border-charcoal')); this.classList.add('border-charcoal');">
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="flex flex-col lg:sticky lg:top-28 h-fit">
                    @if($product->brand)
                        <a href="{{ route('shop', ['brand' => $product->brand_id]) }}" class="hover:text-accent transition-colors block w-fit">
                            <h2 class="text-accent uppercase tracking-[0.2em] text-xs font-medium mb-3">{{ $product->brand->name }}</h2>
                        </a>
                    @endif
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif text-charcoal mb-3 leading-tight">{{ $product->name }}</h1>
                    @if($product->concentration)
                        <p class="text-neutral-500 text-lg mb-6">{{ $product->concentration }}</p>
                    @else
                        <div class="mb-6"></div>
                    @endif
                    
                    <div class="flex items-center space-x-4 mb-8 pb-8 border-b border-neutral-200">
                        <span id="product-price" class="text-2xl text-charcoal font-serif">{{ number_format($product->price) }} LE</span>
                        @if($product->original_price)
                        <span class="text-lg text-neutral-400 line-through">{{ number_format($product->original_price) }} LE</span>
                        @endif
                    </div>

                    <p class="text-neutral-600 leading-relaxed mb-10">
                        {{ $product->description }}
                    </p>

                    <!-- Olfactory Pyramid -->
                    @if($product->notes->isNotEmpty())
                    <div class="mb-10 p-6 bg-neutral-100 border border-neutral-200">
                        <h3 class="text-charcoal font-serif text-lg mb-4 text-center">Olfactory Pyramid</h3>
                        <div class="space-y-4">
                            @foreach(['top' => 'Top Notes', 'heart' => 'Heart Notes', 'base' => 'Base Notes'] as $type => $label)
                                @php $notes = $product->notes->where('pivot.type', $type); @endphp
                                @if($notes->isNotEmpty())
                                <div class="flex flex-col items-center text-center">
                                    <span class="text-accent text-xs uppercase tracking-[0.15em] mb-1">{{ $label }}</span>
                                    <span class="text-charcoal text-sm">{{ $notes->pluck('name')->join(', ') }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="space-y-6 mb-10">
                        <!-- Capacity Selector -->
                        <div id="product-variants-data" data-variants="{{ json_encode($product->variants->map(fn($v) => ['id' => $v->id, 'capacity' => $v->capacity . ' ' . $v->unit, 'price' => number_format($v->price), 'raw_price' => $v->price, 'qty' => $v->quantity])) }}" class="hidden"></div>
                        
                        @if($uniqueCapacities->isNotEmpty())
                        <div>
                            <label class="text-xs uppercase tracking-[0.15em] text-charcoal font-medium mb-4 block">Select Size</label>
                            <div class="flex flex-wrap gap-3" id="capacity-container">
                                @foreach($uniqueCapacities as $variant)
                                <button type="button" 
                                    class="product-capacity-btn px-5 py-3 border border-neutral-300 text-neutral-600 font-medium text-sm hover:border-charcoal hover:text-charcoal transition-all duration-300" 
                                    data-capacity="{{ $variant['label'] }}">
                                    {{ $variant['label'] }}
                                </button>
                                @endforeach
                            </div>
                            <input type="hidden" id="selected-capacity">
                        </div>
                        @else
                            <input type="hidden" id="selected-capacity" value="Standard">
                        @endif

                        <!-- Quantity & Add -->
                        <div class="flex gap-3 items-stretch">
                            <div class="flex items-center border border-neutral-300 w-32">
                                <button id="qty-minus" type="button" class="w-10 h-full text-neutral-500 hover:text-charcoal hover:bg-neutral-100 transition-colors text-xl flex items-center justify-center border-r border-neutral-300 focus:outline-none">−</button>
                                <input id="quantity-input" type="number" value="1" min="1" max="10" class="flex-1 w-full bg-transparent text-center text-charcoal font-medium h-12 border-none focus:ring-0 appearance-none text-base no-spinner">
                                <button id="qty-plus" type="button" class="w-10 h-full text-neutral-500 hover:text-charcoal hover:bg-neutral-100 transition-colors text-xl flex items-center justify-center border-l border-neutral-300 focus:outline-none">+</button>
                            </div>
                            <button 
                                id="add-to-cart-btn"
                                class="flex-1 btn-primary disabled:opacity-50 disabled:cursor-not-allowed">
                                Add to Cart
                            </button>
                            
                            <button 
                                type="button" 
                                class="wishlist-toggle w-12 flex items-center justify-center border border-neutral-300 text-neutral-500 hover:border-charcoal hover:text-charcoal transition-colors duration-300"
                                data-id="{{ $product->id }}"
                                aria-label="Add to Wishlist">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <x-product-feature-list />
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="mt-20 border-t border-neutral-200 pt-16">
                <div class="flex items-center justify-between mb-10">
                    <h2 class="text-2xl font-serif text-charcoal">Customer Reviews</h2>
                    <div class="flex items-center gap-2">
                        <span class="text-3xl font-serif text-accent">{{ number_format($product->average_rating, 1) }}</span>
                        <div class="text-xs text-neutral-500 uppercase tracking-wider text-right">
                            Average<br>Rating
                        </div>
                    </div>
                </div>

                @if($product->reviews_count > 0)
                <div class="text-center mb-10">
                    <button id="toggle-reviews-btn" class="btn-secondary" data-slug="{{ $product->slug }}">
                        Show Customer Reviews
                    </button>
                </div>

                <div id="reviews-container" class="grid grid-cols-1 md:grid-cols-2 gap-6 hidden opacity-0 transition-opacity duration-500">
                    <!-- Reviews loaded via JS -->
                </div>

                <div class="text-center mt-10 hidden" id="load-more-container">
                    <button id="load-more-reviews-btn" class="text-neutral-500 hover:text-charcoal underline text-sm uppercase tracking-wider">
                        Load More
                    </button>
                </div>
                @endif
            </div>

            <!-- Related Products -->
            <div class="mt-20 border-t border-neutral-200 pt-16">
                <div class="text-center mb-10">
                    <p class="text-accent uppercase tracking-[0.3em] text-xs font-medium mb-3">Discover More</p>
                    <h2 class="text-2xl md:text-3xl font-serif text-charcoal">You May Also Like</h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                    <x-product-card :product="$related" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Zoom Modal -->
    <div id="zoom-modal" class="fixed inset-0 z-[100] bg-cream/98 hidden flex flex-col items-center justify-center cursor-zoom-out opacity-0 transition-opacity duration-300">
        <button id="close-zoom" class="absolute top-8 right-8 text-charcoal text-4xl hover:text-accent z-[101]">&times;</button>
        <img id="zoom-img-full" src="" class="max-h-[85vh] max-w-[90vw] object-contain scale-90 transition-transform duration-300">
    </div>
@endsection
