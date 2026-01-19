@extends('layouts.app')

@section('title', $product['name'] . ' | MOON')

@section('content')
    <div class="pt-44 pb-24 bg-moon-dark min-h-screen">
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
                <div class="space-y-6">
                    <div class="aspect-[3/4] overflow-hidden bg-gray-800 relative group border border-gray-800">
                         <img id="main-image" src="{{ $product->image }}" 
                              class="w-full h-full object-cover cursor-zoom-in transition-opacity duration-300" alt="{{ $product->name }}">
                         
                         @if(isset($product->badge) && $product->badge)
                         <span class="absolute top-4 left-4 {{ $product->badge_color ?? 'bg-moon-gold' }} text-white text-xs font-bold px-3 py-1.5 uppercase tracking-widest">{{ $product->badge }}</span>
                         @endif
                    </div>
                    
                    <!-- Thumbnails -->
                    @if($product->images->count() > 0)
                    <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                         <img src="{{ $product->image }}" class="gallery-thumb w-20 h-24 object-cover border-2 border-moon-gold cursor-pointer hover:opacity-80 transition-all">
                         @foreach($product->images as $img)
                         <img src="{{ $img->image_path }}" class="gallery-thumb w-20 h-24 object-cover border-2 border-transparent hover:border-moon-gold cursor-pointer hover:opacity-80 transition-all">
                         @endforeach
                    </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="flex flex-col lg:sticky lg:top-32 h-fit">
                    <h1 class="text-3xl md:text-5xl font-serif text-white mb-6 leading-tight">{{ $product->name }}</h1>
                    
                    <div class="flex items-center space-x-4 mb-8 pb-8 border-b border-gray-800/50">
                        <span class="text-3xl text-moon-gold font-bold font-serif">{{ number_format($product->price) }} LE</span>
                        @if(isset($product->original_price) && $product->original_price)
                        <span class="text-xl text-gray-500 line-through font-light">{{ number_format($product->original_price) }} LE</span>
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

                    <style>
                        /* Hide number input spinners */
                        .no-spinner::-webkit-inner-spin-button, 
                        .no-spinner::-webkit-outer-spin-button { 
                            -webkit-appearance: none; 
                            margin: 0; 
                        }
                        .no-spinner {
                            -moz-appearance: textfield;
                        }
                    </style>

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
        </div>
    </div>

    <!-- Zoom Modal -->
    <div id="zoom-modal" class="fixed inset-0 z-[100] bg-black/95 hidden flex flex-col items-center justify-center cursor-zoom-out opacity-0 transition-opacity duration-300">
        <button id="close-zoom" class="absolute top-8 right-8 text-white text-4xl hover:text-moon-gold z-[101]">&times;</button>
        <img id="zoom-img-full" src="" class="max-h-[85vh] max-w-[90vw] object-contain scale-90 transition-transform duration-300">
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => { 
        // Gallery Swap
        const mainImg = document.getElementById('main-image');
        const thumbs = document.querySelectorAll('.gallery-thumb');
        
        thumbs.forEach(thumb => {
            thumb.addEventListener('click', function() {
                const src = this.getAttribute('src');
                if(!mainImg) return;
                
                mainImg.style.opacity = '0.7';
                setTimeout(() => {
                    mainImg.src = src;
                    mainImg.style.opacity = '1';
                }, 150);

                thumbs.forEach(t => t.classList.remove('border-moon-gold', 'border-2'));
                thumbs.forEach(t => t.classList.add('border-transparent', 'border-2'));
                
                this.classList.remove('border-transparent');
                this.classList.add('border-moon-gold');
            });
        });

        // Zoom Logic
        const zoomModal = document.getElementById('zoom-modal');
        const zoomImg = document.getElementById('zoom-img-full');
        const closeBtn = document.getElementById('close-zoom');

        if(mainImg && zoomModal) {
            mainImg.addEventListener('click', () => {
                zoomImg.src = mainImg.src;
                zoomModal.classList.remove('hidden');
                zoomModal.style.display = 'flex'; // Force flex
                
                // Trigger reflow
                void zoomModal.offsetWidth; 
                
                zoomModal.classList.remove('opacity-0');
                zoomImg.classList.remove('scale-90');
                zoomImg.classList.add('scale-100');
            });

            const closeZoom = () => {
                zoomModal.classList.add('opacity-0');
                zoomImg.classList.remove('scale-100');
                zoomImg.classList.add('scale-90');
                
                setTimeout(() => {
                    zoomModal.classList.add('hidden');
                    zoomModal.style.display = 'none';
                }, 300);
            };

            zoomModal.addEventListener('click', (e) => {
                if(e.target === zoomModal || e.target === closeBtn) closeZoom();
            });
            
            closeBtn.addEventListener('click', closeZoom);
        }
    });
</script>
@endpush
