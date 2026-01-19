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
                        <x-product-card :product="$product" />
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

@endsection
