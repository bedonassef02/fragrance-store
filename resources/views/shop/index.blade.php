@extends('layouts.app')

@section('title', 'Shop | MOON')

@section('content')
    <div class="pt-32 pb-16 bg-moon-dark min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-gray-800 pb-6">
                <div>
                    <h1 class="text-4xl font-serif text-white mb-2">Shop All</h1>
                    <p class="text-gray-400">Showing 12 of 48 products</p>
                </div>
                <div class="flex space-x-4 mt-6 md:mt-0">
                    <select class="bg-transparent text-gray-300 border border-gray-700 px-4 py-2 text-sm focus:border-moon-gold focus:outline-none">
                        <option>Sort by: Featured</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Newest Arrivals</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Sidebar Filters -->
                <aside class="w-full lg:w-64 space-y-8">
                    <div>
                        <h3 class="text-white font-serif text-lg mb-4">Categories</h3>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><a href="#" class="text-moon-gold">All Products</a></li>
                            <li><a href="#" class="hover:text-moon-gold">Abayas</a></li>
                            <li><a href="#" class="hover:text-moon-gold">Kaftans</a></li>
                            <li><a href="#" class="hover:text-moon-gold">Dresses</a></li>
                            <li><a href="#" class="hover:text-moon-gold">Handbags</a></li>
                            <li><a href="#" class="hover:text-moon-gold">Scarves</a></li>
                        </ul>
                    </div>
                </aside>

                <!-- Product Grid -->
                <div class="flex-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @for ($i = 0; $i < 6; $i++)
                            <!-- Product Item (Mocked Loop) -->
                            <div class="group">
                                <div class="relative overflow-hidden aspect-[3/4] mb-4 bg-gray-800">
                                    <img src="https://images.unsplash.com/photo-1583391733956-6c78276477e2?q=80&w=800" class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110" alt="Product">
                                    <button class="absolute bottom-0 w-full bg-moon-gold text-moon-dark py-3 font-bold uppercase text-xs tracking-widest translate-y-full group-hover:translate-y-0 transition-transform duration-300">Add to Bag</button>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-lg font-serif mb-1 text-white group-hover:text-moon-gold transition-colors cursor-pointer">Classic Silk Abaya</h3>
                                    <p class="text-sm font-bold text-gray-400">$295.00</p>
                                </div>
                            </div>
                            
                            <div class="group">
                                <div class="relative overflow-hidden aspect-[3/4] mb-4 bg-gray-800">
                                    <img src="https://images.unsplash.com/photo-1628045620958-8671607590d9?q=80&w=800" class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110" alt="Product">
                                    <button class="absolute bottom-0 w-full bg-moon-gold text-moon-dark py-3 font-bold uppercase text-xs tracking-widest translate-y-full group-hover:translate-y-0 transition-transform duration-300">Add to Bag</button>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-lg font-serif mb-1 text-white group-hover:text-moon-gold transition-colors cursor-pointer">Embroidered Kaftan</h3>
                                    <p class="text-sm font-bold text-gray-400">$450.00</p>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-16 space-x-2">
                        <span class="px-4 py-2 border border-moon-gold text-moon-gold text-sm font-bold">1</span>
                        <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 text-sm hover:border-white hover:text-white transition-colors">2</a>
                        <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 text-sm hover:border-white hover:text-white transition-colors">3</a>
                        <span class="px-4 py-2 text-gray-400 text-sm">...</span>
                        <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 text-sm hover:border-white hover:text-white transition-colors">Next</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
