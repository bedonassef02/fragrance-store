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
    <div class="pt-44 pb-24 bg-moon-dark min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="text-sm mb-8 text-gray-500 font-light">
                <a href="{{ route('home') }}" class="hover:text-moon-gold transition-colors">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('shop') }}" class="hover:text-moon-gold transition-colors">{{ $product->category->name ?? 'Shop' }}</a>
                <span class="mx-2">/</span>
                <span class="text-gray-300">{{ $product->name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Gallery -->
                <div class="space-y-6">
                    <div class="aspect-[3/4] overflow-hidden bg-gray-800 relative group border border-gray-800">
                         <img id="main-image" src="{{ $product->image }}" 
                              class="w-full h-full object-cover cursor-zoom-in transition-opacity duration-300" alt="{{ $product->name }}">
                         
                         @if($product->badge)
                         <span class="absolute top-4 left-4 {{ $product->badge_color ?? 'bg-moon-gold' }} text-white text-xs font-bold px-3 py-1.5 uppercase tracking-widest">{{ $product->badge }}</span>
                         @endif
                    </div>
                    
                    <!-- Thumbnails -->
                    @if($product->images->count() > 0)
                    <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                         <img src="{{ $product->image }}" class="gallery-thumb w-20 h-24 object-cover border-2 border-moon-gold cursor-pointer hover:opacity-80 transition-all" onclick="document.getElementById('main-image').src=this.src; document.querySelectorAll('.gallery-thumb').forEach(el => el.classList.remove('border-moon-gold')); this.classList.add('border-moon-gold');">
                         @foreach($product->images as $img)
                         <img src="{{ $img->image_path }}" 
                              class="gallery-thumb w-20 h-24 object-cover border-2 border-transparent hover:border-moon-gold cursor-pointer hover:opacity-80 transition-all"
                              onclick="document.getElementById('main-image').src=this.src; document.querySelectorAll('.gallery-thumb').forEach(el => el.classList.remove('border-moon-gold')); this.classList.add('border-moon-gold');">
                         @endforeach
                    </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="flex flex-col lg:sticky lg:top-32 h-fit">
                    @if($product->brand)
                        <h2 class="text-moon-gold uppercase tracking-[0.2em] text-sm mb-2">{{ $product->brand->name }}</h2>
                    @endif
                    <h1 class="text-3xl md:text-5xl font-serif text-white mb-2 leading-tight">{{ $product->name }}</h1>
                    @if($product->concentration)
                        <p class="text-gray-400 font-light text-lg mb-6">{{ $product->concentration }}</p>
                    @else
                        <div class="mb-6"></div>
                    @endif
                    
                    <div class="flex items-center space-x-4 mb-8 pb-8 border-b border-gray-800/50">
                        <span id="product-price" class="text-3xl text-moon-gold font-bold font-serif">{{ number_format($product->price) }} LE</span>
                        @if($product->original_price)
                        <span class="text-xl text-gray-500 line-through font-light">{{ number_format($product->original_price) }} LE</span>
                        @endif
                    </div>

                    <p class="text-gray-400 leading-relaxed mb-10 font-light text-lg">
                        {{ $product->description }}
                    </p>

                    <!-- Olfactory Pyramid -->
                    @if($product->notes->isNotEmpty())
                    <div class="mb-10 p-6 bg-white/5 border border-white/5 rounded-sm">
                        <h3 class="text-white font-serif text-lg mb-4 text-center">Olfactory Pyramid</h3>
                        <div class="space-y-4">
                            @foreach(['top' => 'Top Notes', 'heart' => 'Heart Notes', 'base' => 'Base Notes'] as $type => $label)
                                @php $notes = $product->notes->where('pivot.type', $type); @endphp
                                @if($notes->isNotEmpty())
                                <div class="flex flex-col items-center text-center">
                                    <span class="text-moon-gold text-xs uppercase tracking-widest mb-1">{{ $label }}</span>
                                    <span class="text-gray-300 text-sm">{{ $notes->pluck('name')->join(', ') }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="space-y-8 mb-12">
                         <!-- Capacity Selector -->
                        <div id="product-variants-data" data-variants="{{ json_encode($product->variants->map(fn($v) => ['id' => $v->id, 'capacity' => $v->capacity . ' ' . $v->unit, 'price' => number_format($v->price), 'raw_price' => $v->price, 'qty' => $v->quantity])) }}" class="hidden"></div>
                        
                        @if($uniqueCapacities->isNotEmpty())
                        <div>
                            <div class="flex justify-between mb-4">
                                <label class="text-xs uppercase tracking-widest text-white font-bold">Select Capacity</label>
                            </div>
                            <div class="flex flex-wrap gap-3" id="capacity-container">
                                @foreach($uniqueCapacities as $variant)
                                <button type="button" 
                                    class="product-capacity-btn px-6 py-3 border border-gray-700 text-gray-400 font-bold hover:border-moon-gold hover:text-white transition-all duration-300 focus:bg-moon-gold focus:text-black focus:border-moon-gold" 
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
                        <div class="flex gap-4 items-stretch h-14">
                            <div class="flex items-center border border-gray-600 w-40 rounded-sm bg-gray-800/50">
                                <button id="qty-minus" type="button" class="w-12 h-full text-gray-300 hover:text-white hover:bg-gray-700 transition-colors text-2xl flex items-center justify-center border-r border-gray-600 focus:outline-none active:bg-gray-600 font-light pb-1">-</button>
                                <input id="quantity-input" type="number" value="1" min="1" max="10" class="flex-1 w-full bg-transparent text-center text-white font-bold h-full border-none focus:ring-0 appearance-none m-0 text-lg no-spinner">
                                <button id="qty-plus" type="button" class="w-12 h-full text-gray-300 hover:text-white hover:bg-gray-700 transition-colors text-2xl flex items-center justify-center border-l border-gray-600 focus:outline-none active:bg-gray-600 font-light pb-1">+</button>
                            </div>
                            <button id="add-to-bag-btn" data-id="{{ $product->id }}" class="flex-1 bg-moon-gold text-moon-dark font-bold uppercase tracking-widest hover:bg-white transition-colors h-full text-sm shadow-[0_0_20px_rgba(198,168,124,0.2)] hover:shadow-[0_0_30px_rgba(198,168,124,0.4)] px-8">
                                Add to Bag
                            </button>
                        </div>
                    </div>



                    <x-product-feature-list />
                </div>
            </div>

            <!-- Reviews and Related Sections (Keep existing style) -->
            <!-- ... (Reviews code same as before but ensured layout consistency) ... -->
             <!-- Reviews -->
            <div class="mt-24 border-t border-gray-800 pt-16">
                <div class="flex items-center justify-between mb-12">
                     <h2 class="text-2xl font-serif text-white">Customer Reviews</h2>
                     <div class="flex items-center gap-2">
                        <span class="text-4xl font-serif text-moon-gold">{{ number_format($product->average_rating, 1) }}</span>
                        <div class="text-xs text-gray-500 uppercase tracking-widest text-right">
                           Average<br>Rating
                        </div>
                     </div>
                </div>

                @if($product->reviews_count > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($product->reviews as $review)
                    <div class="bg-white/5 border border-white/10 p-6 rounded-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="flex text-moon-gold text-sm mb-1">
                                    @for($i=1; $i<=5; $i++)
                                        @if($i <= $review->rating) ★ @else ☆ @endif
                                    @endfor
                                </div>
                                <span class="text-white text-sm font-bold">{{ $review->user->name ?? 'Verified Buyer' }}</span>
                            </div>
                            <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed mb-4">{{ $review->comment }}</p>
                        @if($review->image_path)
                        <img src="{{ Storage::url($review->image_path) }}" class="w-20 h-20 object-cover rounded-sm border border-gray-700 cursor-zoom-in" onclick="document.getElementById('zoom-img-full').src=this.src; document.getElementById('zoom-modal').classList.remove('hidden', 'opacity-0');">
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 bg-white/5 border border-white/5 border-dashed rounded-sm">
                    <p class="text-gray-500 font-light">No reviews yet. Be the first to share your thoughts!</p>
                </div>
                @endif
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
        </div>
    </div>

    <!-- Zoom Modal -->
    <div id="zoom-modal" class="fixed inset-0 z-[100] bg-black/95 hidden flex flex-col items-center justify-center cursor-zoom-out opacity-0 transition-opacity duration-300">
        <button id="close-zoom" class="absolute top-8 right-8 text-white text-4xl hover:text-moon-gold z-[101]">&times;</button>
        <img id="zoom-img-full" src="" class="max-h-[85vh] max-w-[90vw] object-contain scale-90 transition-transform duration-300">
    </div>
@endsection
