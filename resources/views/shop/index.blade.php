@extends('layouts.app')

@section('title', 'Shop | MOON')

@section('content')
    <div class="pt-44 pb-16 bg-moon-dark min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header (Centered) -->
            <!-- Header (Centered) -->
            <div class="text-center mb-12 border-b border-gray-800 pb-10">
                @if($activeCollection)
                    <div class="mb-6 relative h-64 md:h-80 w-full overflow-hidden rounded-sm animate-fadeInUp">
                        <div class="absolute inset-0 bg-black/40 z-10"></div>
                        <img src="{{ $activeCollection->image }}" alt="{{ $activeCollection->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 z-20 flex flex-col items-center justify-center p-4">
                             <span class="text-moon-gold text-sm uppercase tracking-widest mb-2 block">{{ $activeCollection->subtitle }}</span>
                             <h1 class="text-4xl md:text-5xl font-serif text-white">{{ $activeCollection->title }}</h1>
                        </div>
                    </div>
                @else
                    <h1 class="text-4xl md:text-5xl font-serif text-white mb-4 animate-fadeInUp">Shop All</h1>
                    <p class="text-gray-400 font-light tracking-wide animate-fadeInUp delay-100">Thinking of you. Designed for you.</p>
                @endif
            </div>
            
            <!-- Controls Bar -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 relative py-4">
                 <!-- Mobile Filter Toggle -->
                 <button id="filter-toggle" class="lg:hidden w-full md:w-auto flex justify-center items-center text-moon-gold uppercase tracking-widest text-xs font-bold border border-moon-gold px-8 py-3 hover:bg-moon-gold hover:text-black transition-colors duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    Filters
                </button>

                <div class="text-gray-400 text-sm hidden md:block">
                    Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                </div>

                <div class="w-full md:w-auto relative">
                    <select name="sort" form="filter-form" onchange="this.form.submit()" class="w-full md:w-auto appearance-none bg-moon-dark text-gray-300 border border-gray-700 pl-6 pr-10 py-3 text-xs uppercase tracking-wider focus:border-moon-gold focus:outline-none transition-colors cursor-pointer hover:border-gray-500">
                        <option value="newest" class="bg-moon-dark text-gray-300" {{ request('sort') == 'newest' ? 'selected' : '' }}>Sort by: Newest</option>
                        <option value="price_asc" class="bg-moon-dark text-gray-300" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" class="bg-moon-dark text-gray-300" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                    <!-- Custom Arrow -->
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-12 relative">
                <!-- Sidebar Filters -->
                <aside id="shop-sidebar" class="fixed inset-0 z-50 lg:z-0 bg-moon-dark p-6 overflow-y-auto transform -translate-x-full transition-transform duration-300 lg:relative lg:translate-x-0 lg:w-64 lg:inset-auto lg:p-0 lg:overflow-visible lg:bg-transparent lg:block">
                     <!-- Mobile Close Button -->
                    <div class="lg:hidden flex justify-between items-center mb-8 border-b border-gray-800 pb-4">
                        <span class="text-white font-serif text-xl">Filters</span>
                        <button id="filter-close" class="text-gray-400 hover:text-white p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form action="{{ route('shop') }}" method="GET" id="filter-form">
                        <div class="space-y-10">
                            <!-- Search -->
                            <div>
                                <h3 class="text-white font-serif text-lg mb-4">Search</h3>
                                <div class="relative">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="w-full bg-transparent border border-gray-700 px-4 py-2 text-sm text-white focus:border-moon-gold outline-none">
                                    <button type="submit" class="absolute right-3 top-2.5 text-gray-500 hover:text-moon-gold">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Categories -->
                            <div>
                                <h3 class="text-white font-serif text-lg mb-4 flex items-center justify-between group cursor-pointer">
                                    Categories
                                </h3>
                                <div class="space-y-3">
                                    @foreach($categories as $category)
                                    <label class="flex items-center space-x-3 cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="category[]" value="{{ $category->slug }}" {{ in_array($category->slug, (array)request('category', [])) ? 'checked' : '' }} class="peer h-4 w-4 appearance-none border border-gray-600 rounded-sm checked:bg-moon-gold checked:border-moon-gold transition-all">
                                            <svg class="absolute w-3 h-3 text-black hidden peer-checked:block pointer-events-none left-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <span class="text-sm text-gray-400 group-hover:text-moon-gold transition-colors">{{ $category->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div>
                                <h3 class="text-white font-serif text-lg mb-4">Price Range</h3>
                                <div class="space-y-3 text-sm text-gray-400">
                                    <label class="flex items-center space-x-3 cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="price_range[]" value="0-1500" {{ in_array('0-1500', (array)request('price_range', [])) ? 'checked' : '' }} class="peer h-4 w-4 appearance-none border border-gray-600 rounded-sm checked:bg-moon-gold checked:border-moon-gold transition-all">
                                            <svg class="absolute w-3 h-3 text-black hidden peer-checked:block pointer-events-none left-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <span class="group-hover:text-gray-300 transition-colors">Under 1500 LE</span>
                                    </label>
                                    <label class="flex items-center space-x-3 cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="price_range[]" value="1500-3000" {{ in_array('1500-3000', (array)request('price_range', [])) ? 'checked' : '' }} class="peer h-4 w-4 appearance-none border border-gray-600 rounded-sm checked:bg-moon-gold checked:border-moon-gold transition-all">
                                            <svg class="absolute w-3 h-3 text-black hidden peer-checked:block pointer-events-none left-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <span class="group-hover:text-gray-300 transition-colors">1500 LE - 3000 LE</span>
                                    </label>
                                    <label class="flex items-center space-x-3 cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="price_range[]" value="3000-5000" {{ in_array('3000-5000', (array)request('price_range', [])) ? 'checked' : '' }} class="peer h-4 w-4 appearance-none border border-gray-600 rounded-sm checked:bg-moon-gold checked:border-moon-gold transition-all">
                                            <svg class="absolute w-3 h-3 text-black hidden peer-checked:block pointer-events-none left-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <span class="group-hover:text-gray-300 transition-colors">3000 LE - 5000 LE</span>
                                    </label>
                                     <label class="flex items-center space-x-3 cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="price_range[]" value="5000+" {{ in_array('5000+', (array)request('price_range', [])) ? 'checked' : '' }} class="peer h-4 w-4 appearance-none border border-gray-600 rounded-sm checked:bg-moon-gold checked:border-moon-gold transition-all">
                                            <svg class="absolute w-3 h-3 text-black hidden peer-checked:block pointer-events-none left-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <span class="group-hover:text-gray-300 transition-colors">Above 5000 LE</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-gray-800">
                            <button type="submit" class="w-full bg-moon-gold text-moon-dark py-3 font-bold uppercase tracking-widest text-xs hover:bg-white transition-colors">
                                Apply Filters
                            </button>
                            @if(request()->anyFilled(['search', 'category', 'price_range', 'sort']))
                            <a href="{{ route('shop') }}" class="block text-center mt-4 text-xs text-gray-500 underline hover:text-white">Clear All</a>
                            @endif
                        </div>
                    </form>
                </aside>

                <!-- Product Grid -->
                <div class="flex-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
                        @forelse($products as $product)
                        <x-product-card :product="$product" />
                        @empty
                        <div class="col-span-full text-center py-20 text-gray-500">
                            <p>No products found matching your criteria.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-20">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
        </div>
    </div>

@endsection
