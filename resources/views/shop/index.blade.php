@extends('layouts.app')

@section('title', 'Shop | MOON')

@section('content')
    <div class="pt-28 pb-20 bg-cream min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12 pb-10 border-b border-neutral-200">
                @if($activeBrand)
                    <div class="mb-8 relative h-56 md:h-72 w-full overflow-hidden bg-neutral-100">
                        @if($activeBrand->image)
                        <img src="{{ $activeBrand->image }}" alt="{{ $activeBrand->name }}" class="w-full h-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-charcoal/30"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                            <span class="text-accent text-xs uppercase tracking-[0.3em] mb-3">Local Brand</span>
                            <h1 class="text-4xl md:text-5xl font-serif text-white">{{ $activeBrand->name }}</h1>
                        </div>
                    </div>
                @elseif($activeCollection)
                    <div class="mb-8 relative h-56 md:h-72 w-full overflow-hidden bg-neutral-100">
                        <img src="{{ $activeCollection->image }}" alt="{{ $activeCollection->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-charcoal/30"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                            <span class="text-accent text-xs uppercase tracking-[0.3em] mb-3">{{ $activeCollection->subtitle }}</span>
                            <h1 class="text-4xl md:text-5xl font-serif text-white">{{ $activeCollection->title }}</h1>
                        </div>
                    </div>
                @else
                    <x-shop.ui.section-header 
                        title="Shop All" 
                        subtitle="Discover" 
                    >
                        Curated scents for every occasion
                    </x-shop.ui.section-header>
                @endif
            </div>
            
            <!-- Controls Bar -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 py-4">
                <!-- Mobile Filter Toggle -->
                <button id="filter-toggle" class="lg:hidden w-full md:w-auto flex justify-center items-center text-charcoal uppercase tracking-[0.15em] text-xs font-medium border border-charcoal px-8 py-3 hover:bg-charcoal hover:text-cream transition-colors duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filters
                </button>

                <div class="text-neutral-500 text-sm hidden md:block">
                    Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                </div>

                <div class="w-full md:w-auto relative">
                    <select name="sort" form="filter-form" onchange="this.form.submit()" class="w-full md:w-auto appearance-none bg-cream text-charcoal border border-neutral-300 pl-4 pr-10 py-2.5 text-xs uppercase tracking-wider focus:border-charcoal focus:outline-none transition-colors cursor-pointer hover:border-neutral-400">
                        <option value="-created_at" {{ request('sort') == '-created_at' ? 'selected' : '' }}>Sort by: Newest</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="-price" {{ request('sort') == '-price' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-neutral-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-12 relative">
                <!-- Sidebar Filters -->
                <aside id="shop-sidebar" class="fixed inset-0 z-50 lg:z-0 bg-cream p-6 overflow-y-auto transform -translate-x-full transition-transform duration-300 lg:relative lg:translate-x-0 lg:w-64 lg:inset-auto lg:p-0 lg:overflow-visible lg:block">
                    <!-- Mobile Close Button -->
                    <div class="lg:hidden flex justify-between items-center mb-8 border-b border-neutral-200 pb-4">
                        <span class="text-charcoal font-serif text-xl">Filters</span>
                        <button id="filter-close" class="text-neutral-400 hover:text-charcoal p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('shop') }}" method="GET" id="filter-form">
                        <div class="space-y-8">
                            <!-- Search -->
                            <div>
                                <h3 class="text-charcoal font-medium text-sm uppercase tracking-[0.1em] mb-4">Search</h3>
                                <x-shop.form.input name="search" :value="request('search')" placeholder="Search fragrances...">
                                    <button type="submit" class="hover:text-charcoal transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </button>
                                </x-shop.form.input>
                            </div>

                            <!-- Categories -->
                            <div>
                                <h3 class="text-charcoal font-medium text-sm uppercase tracking-[0.1em] mb-4">Categories</h3>
                                <div class="space-y-3">
                                    @foreach($categories as $category)
                                        <x-shop.form.checkbox 
                                            name="category[]" 
                                            :value="$category->slug" 
                                            :label="$category->name"
                                            :checked="in_array($category->slug, (array)request('category', []))"
                                        />
                                    @endforeach
                                </div>
                            </div>

                            <!-- Brands -->
                            <div>
                                <h3 class="text-charcoal font-medium text-sm uppercase tracking-[0.1em] mb-4">Brands</h3>
                                <x-shop.form.input id="brand-search" placeholder="Search brands..." class="text-xs mb-4" />
                                <div class="space-y-3 max-h-48 overflow-y-auto" id="brand-list">
                                    @foreach($brands as $brand)
                                    <x-shop.form.checkbox 
                                        name="brand[]" 
                                        :value="$brand->slug" 
                                        :label="$brand->name"
                                        :checked="in_array($brand->slug, (array)request('brand', []))"
                                        id="brand-{{ $brand->slug }}"
                                        class="brand-item"
                                        data-name="{{ strtolower($brand->name) }}"
                                    />
                                    @endforeach
                                </div>
                            </div>

                            <!-- Concentration -->
                            <div>
                                <h3 class="text-charcoal font-medium text-sm uppercase tracking-[0.1em] mb-4">Concentration</h3>
                                <div class="space-y-3">
                                    @foreach($uniqueConcentrations as $concentration)
                                    <x-shop.form.checkbox 
                                        name="concentration[]" 
                                        :value="$concentration" 
                                        :label="$concentration"
                                        id="conc-{{ Str::slug($concentration) }}"
                                        :checked="in_array($concentration, (array)request('concentration', []))"
                                    />
                                    @endforeach
                                </div>
                            </div>

                            <!-- Capacity -->
                            <div>
                                <h3 class="text-charcoal font-medium text-sm uppercase tracking-[0.1em] mb-4">Size</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($uniqueCapacities as $capacity)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="capacity[]" value="{{ $capacity }}" {{ in_array($capacity, (array)request('capacity', [])) ? 'checked' : '' }} class="peer hidden">
                                        <span class="block px-3 py-2 border border-neutral-300 text-neutral-600 text-xs font-medium peer-checked:bg-charcoal peer-checked:text-cream peer-checked:border-charcoal hover:border-charcoal transition-all">
                                            {{ $capacity }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Availability -->
                            <div>
                                <h3 class="text-charcoal font-medium text-sm uppercase tracking-[0.1em] mb-4">Availability</h3>
                                <x-shop.form.checkbox 
                                    name="in_stock" 
                                    value="1" 
                                    label="In Stock Only"
                                    :checked="request()->boolean('in_stock')"
                                />
                            </div>

                            <!-- Price Range -->
                            <div>
                                <h3 class="text-charcoal font-medium text-sm uppercase tracking-[0.1em] mb-4">Price Range (LE)</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="price_min" class="block text-xs text-neutral-500 mb-2">Min</label>
                                        <x-shop.form.input type="number" name="price_min" :value="request('price_min')" placeholder="0" min="0" step="100" />
                                    </div>
                                    <div>
                                        <label for="price_max" class="block text-xs text-neutral-500 mb-2">Max</label>
                                        <x-shop.form.input type="number" name="price_max" :value="request('price_max')" placeholder="10000" min="0" step="100" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-10 pt-6 border-t border-neutral-200 space-y-3">
                            <x-shop.ui.button type="submit" class="w-full">
                                Apply Filters
                            </x-shop.ui.button>
                            @if(request()->anyFilled(['search', 'category', 'brand', 'concentration', 'capacity', 'price_min', 'price_max', 'sort', 'in_stock']))
                            <x-shop.ui.button :href="route('shop')" variant="secondary" class="w-full block text-center">
                                Clear All Filters
                            </x-shop.ui.button>
                            @endif
                        </div>
                    </form>
                </aside>

                <!-- Product Grid -->
                <div class="flex-1">
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10">
                        @forelse($products as $product)
                        <x-product-card :product="$product" />
                        @empty
                        <div class="col-span-full text-center py-20">
                            <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-neutral-500">No products found matching your criteria.</p>
                            <x-shop.ui.button :href="route('shop')" variant="link" class="mt-4 text-sm">Clear filters</x-shop.ui.button>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-16">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
