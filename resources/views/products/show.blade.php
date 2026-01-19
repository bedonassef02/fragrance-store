@extends('layouts.app')

@section('title', $product['name'] . ' | MOON')

@section('content')
    <div class="pt-32 pb-24 bg-moon-dark min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="text-sm mb-8 text-gray-500 font-light">
                <a href="{{ route('home') }}" class="hover:text-moon-gold transition-colors">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('shop') }}" class="hover:text-moon-gold transition-colors">{{ $product['category'] ?? 'Shop' }}</a>
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
                <div class="flex flex-col">
                    <h1 class="text-3xl md:text-5xl font-serif text-white mb-4">{{ $product['name'] }}</h1>
                    <div class="flex items-center space-x-4 mb-8">
                        <span class="text-2xl text-moon-gold font-bold">{{ number_format($product['price']) }} LE</span>
                        @if(isset($product['original_price']) && $product['original_price'])
                        <span class="text-lg text-gray-500 line-through">{{ number_format($product['original_price']) }} LE</span>
                        @endif
                        @if(isset($product['badge']) && $product['badge'])
                        <span class="text-xs font-bold {{ $product['badge_color'] ?? 'bg-red-600' }} text-white px-2 py-1 uppercase tracking-widest">{{ $product['badge'] }}</span>
                        @endif
                    </div>

                    <p class="text-gray-400 leading-relaxed mb-8 font-light">
                        {{ $product['description'] }}
                    </p>

                    <div class="space-y-6 mb-10">
                         <!-- Size Selector -->
                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="text-xs uppercase tracking-widest text-white">Size</label>
                                <a href="#" class="text-xs text-gray-500 underline hover:text-moon-gold">Size Guide</a>
                            </div>
                            <div class="flex space-x-3">
                                <button class="product-size-btn w-12 h-12 flex items-center justify-center border border-gray-700 text-gray-400 hover:border-moon-gold hover:text-moon-gold transition-all" data-size="S">S</button>
                                <button class="product-size-btn w-12 h-12 flex items-center justify-center border border-gray-700 text-gray-400 hover:border-moon-gold hover:text-moon-gold transition-all" data-size="M">M</button>
                                <button class="product-size-btn w-12 h-12 flex items-center justify-center border border-gray-700 text-gray-400 hover:border-moon-gold hover:text-moon-gold transition-all" data-size="L">L</button>
                                <button class="product-size-btn w-12 h-12 flex items-center justify-center border border-gray-700 text-gray-400 hover:border-moon-gold hover:text-moon-gold transition-all" data-size="XL">XL</button>
                            </div>
                        </div>

                        <!-- Quantity & Add -->
                        <div class="flex space-x-4">
                            <div class="flex items-center border border-gray-700">
                                <button id="qty-minus" class="px-3 py-4 text-gray-400 hover:text-white">-</button>
                                <input id="quantity-input" type="number" value="1" class="w-12 bg-transparent text-center text-white focus:outline-none appearance-none m-0">
                                <button id="qty-plus" class="px-3 py-4 text-gray-400 hover:text-white">+</button>
                            </div>
                            <button id="add-to-bag-btn" class="flex-1 bg-moon-gold text-moon-dark font-bold uppercase tracking-widest hover:bg-white transition-colors py-4">
                                Add to Bag
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 pt-8 space-y-4">
                        <div class="flex items-start space-x-3 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-moon-gold mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Premium Quality Fabric</span>
                        </div>
                        <div class="flex items-start space-x-3 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-moon-gold mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Dispatched within 24 hours</span>
                        </div>
                         <div class="flex items-start space-x-3 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-moon-gold mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span>Free returns within 30 days</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
             <div class="mt-24 border-t border-gray-800 pt-16">
                <h2 class="text-2xl font-serif text-white mb-8 text-center">You May Also Like</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                    <div class="group">
                        <div class="relative overflow-hidden aspect-[3/4] mb-3 bg-gray-800">
                            <img src="{{ $related['image'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="{{ $related['name'] }}">
                        </div>
                        <a href="{{ route('product.show', $related['id']) }}">
                            <h3 class="text-white text-sm font-serif group-hover:text-moon-gold cursor-pointer">{{ $related['name'] }}</h3>
                        </a>
                        <p class="text-gray-400 text-xs font-bold">{{ number_format($related['price']) }} LE</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sizeBtns = document.querySelectorAll('.product-size-btn');
            const qtyInput = document.getElementById('quantity-input');
            const qtyMinus = document.getElementById('qty-minus');
            const qtyPlus = document.getElementById('qty-plus');
            const addToBagBtn = document.getElementById('add-to-bag-btn');
            let selectedSize = null;

            // Size Selection
            sizeBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    sizeBtns.forEach(b => {
                        b.classList.remove('bg-moon-gold', 'text-moon-dark', 'font-bold', 'shadow-[0_0_10px_rgba(198,168,124,0.3)]');
                        b.classList.add('border-gray-700', 'text-gray-400');
                    });
                    
                    btn.classList.remove('border-gray-700', 'text-gray-400');
                    btn.classList.add('bg-moon-gold', 'text-moon-dark', 'font-bold', 'shadow-[0_0_10px_rgba(198,168,124,0.3)]');
                    
                    selectedSize = btn.dataset.size;
                });
            });

            // Quantity Logic
            if(qtyMinus) {
                qtyMinus.addEventListener('click', () => {
                    let val = parseInt(qtyInput.value);
                    if(val > 1) qtyInput.value = val - 1;
                });
            }
            if(qtyPlus) {
                qtyPlus.addEventListener('click', () => {
                    let val = parseInt(qtyInput.value);
                    qtyInput.value = val + 1;
                });
            }

            // Add to Bag Logic
            if(addToBagBtn) {
                addToBagBtn.addEventListener('click', () => {
                   if (!selectedSize) {
                        alert('Please select a size');
                        return;
                    }

                    addToBagBtn.innerText = 'Adding...';
                    addToBagBtn.disabled = true;

                    fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            product_id: {{ $product['id'] }},
                            size: selectedSize,
                            quantity: parseInt(qtyInput.value)
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        addToBagBtn.innerText = 'Add to Bag';
                        addToBagBtn.disabled = false;
                        
                        if (data.success) {
                            alert('Added to Bag!'); // Could be a toast if we moved toast to layout
                            
                            // Check for header badge
                            const oldBadges = document.querySelectorAll('.bg-moon-gold.rounded-full');
                            oldBadges.forEach(badge => {
                                badge.innerText = data.cartCount;
                                badge.classList.remove('hidden');
                            });
                        } else {
                            alert(data.error || 'Something went wrong');
                        }
                    })
                    .catch(e => {
                        console.error(e);
                        addToBagBtn.innerText = 'Add to Bag';
                        addToBagBtn.disabled = false;
                    });
                });
            }
        });
    </script>
    @endpush
@endsection
