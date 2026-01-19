@extends('layouts.app')

@section('title', 'Shop | MOON')

@section('content')
    <div class="pt-32 pb-16 bg-moon-dark min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header (Centered) -->
            <div class="text-center mb-12 border-b border-gray-800 pb-10">
                <h1 class="text-4xl md:text-5xl font-serif text-white mb-4 animate-fadeInUp">Shop All</h1>
                <p class="text-gray-400 font-light tracking-wide animate-fadeInUp delay-100">Thinking of you. Designed for you.</p>
            </div>
            
            <!-- Controls Bar -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 sticky top-20 z-30 bg-moon-dark/95 py-4 backdrop-blur-md">
                 <!-- Mobile Filter Toggle -->
                 <button id="filter-toggle" class="lg:hidden w-full md:w-auto flex justify-center items-center text-moon-gold uppercase tracking-widest text-xs font-bold border border-moon-gold px-8 py-3 hover:bg-moon-gold hover:text-black transition-colors duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    Filters
                </button>

                <div class="text-gray-400 text-sm hidden md:block">
                    Showing 12 of 48 products
                </div>

                <div class="w-full md:w-auto">
                    <select class="w-full md:w-auto bg-transparent text-gray-300 border border-gray-700 px-6 py-3 text-xs uppercase tracking-wider focus:border-moon-gold focus:outline-none transition-colors cursor-pointer hover:border-gray-500">
                        <option>Sort by: Featured</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Newest Arrivals</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-12 relative">
                <!-- Sidebar Filters -->
                <aside id="shop-sidebar" class="fixed inset-0 z-50 bg-moon-dark p-6 overflow-y-auto transform -translate-x-full transition-transform duration-300 lg:relative lg:translate-x-0 lg:w-64 lg:inset-auto lg:p-0 lg:overflow-visible lg:bg-transparent lg:block">
                     <!-- Mobile Close Button -->
                    <div class="lg:hidden flex justify-between items-center mb-8 border-b border-gray-800 pb-4">
                        <span class="text-white font-serif text-xl">Filters</span>
                        <button id="filter-close" class="text-gray-400 hover:text-white p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="space-y-10">
                        <div>
                            <h3 class="text-white font-serif text-lg mb-6 flex items-center justify-between group cursor-pointer">
                                Categories
                                <svg class="w-4 h-4 text-gray-500 group-hover:text-moon-gold transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </h3>
                            <ul class="space-y-3 text-sm text-gray-400">
                                <li><a href="#" class="text-moon-gold font-bold flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-moon-gold mr-3"></span>All Products</a></li>
                                <li><a href="#" class="hover:text-moon-gold transition-colors block pl-4.5">Luxury Abayas</a></li>
                                <li><a href="#" class="hover:text-moon-gold transition-colors block pl-4.5">Evening Kaftans</a></li>
                                <li><a href="#" class="hover:text-moon-gold transition-colors block pl-4.5">Embroidered Dresses</a></li>
                                <li><a href="#" class="hover:text-moon-gold transition-colors block pl-4.5">Handbags & Clutches</a></li>
                                <li><a href="#" class="hover:text-moon-gold transition-colors block pl-4.5">Silk Scarves</a></li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-white font-serif text-lg mb-6">Price Range</h3>
                            <div class="space-y-3 text-sm text-gray-400">
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" class="peer h-4 w-4 appearance-none border border-gray-600 rounded-sm checked:bg-moon-gold checked:border-moon-gold transition-all">
                                        <svg class="absolute w-3 h-3 text-black hidden peer-checked:block pointer-events-none left-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <span class="group-hover:text-gray-300 transition-colors">Under 1500 LE</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" class="peer h-4 w-4 appearance-none border border-gray-600 rounded-sm checked:bg-moon-gold checked:border-moon-gold transition-all">
                                        <svg class="absolute w-3 h-3 text-black hidden peer-checked:block pointer-events-none left-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <span class="group-hover:text-gray-300 transition-colors">1500 LE - 3000 LE</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" class="peer h-4 w-4 appearance-none border border-gray-600 rounded-sm checked:bg-moon-gold checked:border-moon-gold transition-all">
                                        <svg class="absolute w-3 h-3 text-black hidden peer-checked:block pointer-events-none left-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <span class="group-hover:text-gray-300 transition-colors">3000 LE - 5000 LE</span>
                                </label>
                                 <label class="flex items-center space-x-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" class="peer h-4 w-4 appearance-none border border-gray-600 rounded-sm checked:bg-moon-gold checked:border-moon-gold transition-all">
                                        <svg class="absolute w-3 h-3 text-black hidden peer-checked:block pointer-events-none left-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <span class="group-hover:text-gray-300 transition-colors">Above 5000 LE</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-12 pt-8 border-t border-gray-800 lg:hidden">
                        <button id="apply-filters" class="w-full bg-moon-gold text-moon-dark py-4 font-bold uppercase tracking-widest text-sm hover:bg-white transition-colors">
                            Apply Filters
                        </button>
                    </div>
                </aside>

                <!-- Product Grid -->
                <div class="flex-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
                        @foreach($products as $product)
                        <div class="group relative fade-in">
                            <div class="relative overflow-hidden aspect-[4/5] mb-4 bg-gray-800">
                                <img src="{{ $product['image'] }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110" alt="{{ $product['name'] }}">
                                
                                @if(isset($product['badge']) && $product['badge'])
                                <span class="absolute top-4 left-4 {{ $product['badge_color'] ?? 'bg-moon-gold' }} text-white text-[10px] font-bold px-3 py-1.5 uppercase tracking-widest">{{ $product['badge'] }}</span>
                                @endif

                                <button data-id="{{ $product['id'] }}" class="quick-add-btn absolute bottom-0 w-full bg-moon-gold text-moon-dark py-4 font-bold uppercase text-xs tracking-widest translate-y-full group-hover:translate-y-0 transition-transform duration-300 hover:bg-white border-t border-moon-dark/10">Add to Bag</button>
                            </div>
                            <div class="text-center group-hover:-translate-y-1 transition-transform duration-300">
                                <a href="{{ route('product.show', $product['id']) }}" class="block">
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
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-20 space-x-2">
                        <span class="px-5 py-3 border border-moon-gold bg-moon-gold text-moon-dark text-sm font-bold transition-transform hover:scale-105">1</span>
                        <a href="#" class="px-5 py-3 border border-gray-700 text-gray-400 text-sm hover:border-white hover:text-white transition-all hover:scale-105">2</a>
                        <a href="#" class="px-5 py-3 border border-gray-700 text-gray-400 text-sm hover:border-white hover:text-white transition-all hover:scale-105">3</a>
                        <span class="px-5 py-3 text-gray-400 text-sm flex items-center">...</span>
                        <a href="#" class="px-5 py-3 border border-gray-700 text-gray-400 text-sm hover:border-white hover:text-white transition-all hover:scale-105">Next</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Backdrop for mobile filter & modal -->
    <div id="backdrop" class="fixed inset-0 bg-black/80 z-40 hidden backdrop-blur-sm transition-opacity opacity-0"></div>

    <!-- Quick Add Modal -->
    <div id="quick-add-modal" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-moon-dark border border-gray-700 p-8 z-50 w-full max-w-md hidden opacity-0 transition-all duration-300 scale-95 shadow-2xl">
        <button id="close-modal" class="absolute top-4 right-4 text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        
        <h3 id="modal-product-title" class="text-2xl font-serif text-white mb-2">Product Name</h3>
        <p id="modal-product-price" class="text-moon-gold font-bold mb-6">0 LE</p>
        
        <div class="mb-8">
            <label class="block text-xs uppercase tracking-widest text-gray-400 mb-3">Select Size</label>
            <div class="flex gap-3">
                <button class="size-btn w-10 h-10 border border-gray-600 text-gray-400 hover:border-moon-gold hover:text-white transition-colors focus:bg-moon-gold focus:text-black focus:border-moon-gold">S</button>
                <button class="size-btn w-10 h-10 border border-gray-600 text-gray-400 hover:border-moon-gold hover:text-white transition-colors focus:bg-moon-gold focus:text-black focus:border-moon-gold">M</button>
                <button class="size-btn w-10 h-10 border border-gray-600 text-gray-400 hover:border-moon-gold hover:text-white transition-colors focus:bg-moon-gold focus:text-black focus:border-moon-gold">L</button>
                <button class="size-btn w-10 h-10 border border-gray-600 text-gray-400 hover:border-moon-gold hover:text-white transition-colors focus:bg-moon-gold focus:text-black focus:border-moon-gold">XL</button>
            </div>
        </div>

        <button id="confirm-add-to-bag" class="w-full bg-moon-gold text-moon-dark font-bold uppercase tracking-widest py-4 hover:bg-white transition-colors">
            Add to Bag
        </button>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-8 right-8 bg-white text-black px-6 py-4 shadow-2xl transform translate-y-20 opacity-0 transition-all duration-500 z-50 flex items-center gap-3 border-l-4 border-moon-gold">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <div>
            <h4 class="font-bold text-sm uppercase tracking-wider">Added to Bag</h4>
            <p class="text-xs text-gray-500">The item has been added to your cart.</p>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterToggle = document.getElementById('filter-toggle');
            const filterClose = document.getElementById('filter-close');
            const sidebar = document.getElementById('shop-sidebar');
            const backdrop = document.getElementById('backdrop');
            const applyBtn = document.getElementById('apply-filters');
            
            // Modal Elements
            const modal = document.getElementById('quick-add-modal');
            const closeModalBtn = document.getElementById('close-modal');
            const modalTitle = document.getElementById('modal-product-title');
            const modalPrice = document.getElementById('modal-product-price');
            const addToBagBtns = document.querySelectorAll('.quick-add-btn');
            const confirmBtn = document.getElementById('confirm-add-to-bag');
            const toast = document.getElementById('toast');
            const sizeBtns = document.querySelectorAll('.size-btn');

            // Toggle Mobile Filter
            function openFilters() {
                sidebar.classList.remove('-translate-x-full');
                showBackdrop();
                document.body.style.overflow = 'hidden';
            }

            function closeFilters() {
                sidebar.classList.add('-translate-x-full');
                hideBackdrop();
                document.body.style.overflow = '';
            }

            // Modal Logic
            function openModal(title, price) {
                modalTitle.textContent = title;
                modalPrice.textContent = price;
                modal.classList.remove('hidden');
                showBackdrop();
                // Animation frame
                setTimeout(() => {
                    modal.classList.remove('opacity-0', 'scale-95');
                    modal.classList.add('opacity-100', 'scale-100');
                }, 10);
            }

            function closeModal() {
                modal.classList.remove('opacity-100', 'scale-100');
                modal.classList.add('opacity-0', 'scale-95');
                hideBackdrop();
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            function showBackdrop() {
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                }, 10);
            }

            function hideBackdrop() {
                backdrop.classList.add('opacity-0');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
            }

            function showToast() {
                toast.classList.remove('translate-y-20', 'opacity-0');
                setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 3000);
            }

            let selectedProductId = null;
            let selectedSize = null;

            // Event Listeners
            if (filterToggle) filterToggle.addEventListener('click', openFilters);
            if (filterClose) filterClose.addEventListener('click', closeFilters);
            if (applyBtn) applyBtn.addEventListener('click', closeFilters);
            
            // Shared Backdrop Click
            if (backdrop) backdrop.addEventListener('click', () => {
                closeFilters();
                closeModal();
            });

            if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);

            addToBagBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation(); // Prevent going to product page
                    
                    selectedProductId = btn.dataset.id;
                    selectedSize = null; // Reset size
                    // Reset size buttons visual state
                    sizeBtns.forEach(b => b.classList.remove('bg-moon-gold', 'text-black', 'border-moon-gold'));
                    sizeBtns.forEach(b => b.classList.add('text-gray-400', 'border-gray-600'));

                    const productCard = btn.closest('.group');
                    const title = productCard.querySelector('h3').innerText;
                    // Find price
                    const priceElement = productCard.querySelector('.text-moon-gold') || productCard.querySelector('.text-gray-400'); 
                    const price = priceElement ? priceElement.innerText : 'Price';
                    
                    openModal(title, price);
                });
            });

            // Size Selection
            sizeBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    sizeBtns.forEach(b => b.classList.remove('bg-moon-gold', 'text-black', 'border-moon-gold'));
                    btn.classList.add('bg-moon-gold', 'text-black', 'border-moon-gold');
                    btn.classList.remove('text-gray-400', 'border-gray-600');
                    selectedSize = btn.innerText;
                });
            });

            if (confirmBtn) confirmBtn.addEventListener('click', () => {
                if (!selectedSize) {
                    alert('Please select a size');
                    return;
                }

                confirmBtn.innerText = 'Adding...';
                confirmBtn.disabled = true;

                fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: selectedProductId,
                        size: selectedSize,
                        quantity: 1
                    })
                })
                .then(response => response.json())
                .then(data => {
                    confirmBtn.innerText = 'Add to Bag';
                    confirmBtn.disabled = false;
                    
                    if (data.success) {
                        closeModal();
                        showToast();
                        
                        const cartCount = document.querySelector('.cart-count-badge'); // We will add this class to header
                        if (cartCount) {
                            cartCount.innerText = data.cartCount;
                            cartCount.classList.remove('hidden');
                        } else {
                            // Fallback to searching by style if class not added yet
                            const oldBadges = document.querySelectorAll('.bg-moon-gold.rounded-full');
                            oldBadges.forEach(badge => {
                                badge.innerText = data.cartCount;
                                badge.classList.remove('hidden');
                            });
                        }
                    } else {
                        alert(data.error || 'Something went wrong');
                    }
                })
                .catch(err => {
                    console.error(err);
                    confirmBtn.innerText = 'Add to Bag';
                    confirmBtn.disabled = false;
                });
            });
        });
    </script>
    @endpush
@endsection
