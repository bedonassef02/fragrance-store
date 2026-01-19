@extends('layouts.app')

@section('title', 'Royal Black Abaya | MOON')

@section('content')
    <div class="pt-32 pb-24 bg-moon-dark min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="text-sm mb-8 text-gray-500 font-light">
                <a href="{{ route('home') }}" class="hover:text-moon-gold transition-colors">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('shop') }}" class="hover:text-moon-gold transition-colors">Abayas</a>
                <span class="mx-2">/</span>
                <span class="text-gray-300">Royal Black Abaya</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Gallery -->
                <div class="space-y-4">
                    <div class="aspect-[3/4] overflow-hidden bg-gray-800 relative group">
                         <img src="https://images.pexels.com/photos/9940866/pexels-photo-9940866.jpeg?auto=compress&cs=tinysrgb&w=1200" 
                              class="w-full h-full object-cover cursor-zoom-in transition-transform duration-700 group-hover:scale-105" alt="Royal Black Abaya">
                    </div>
                    <div class="grid grid-cols-4 gap-4">
                        <button class="aspect-square border border-moon-gold overflow-hidden">
                            <img src="https://images.pexels.com/photos/9940866/pexels-photo-9940866.jpeg?auto=compress&cs=tinysrgb&w=200" class="w-full h-full object-cover">
                        </button>
                        <button class="aspect-square border border-transparent hover:border-moon-gold transition-colors overflow-hidden">
                            <img src="https://images.pexels.com/photos/20344409/pexels-photo-20344409/free-photo-of-woman-in-long-coat-posing-in-passage.jpeg?auto=compress&cs=tinysrgb&w=200" class="w-full h-full object-cover opacity-70 hover:opacity-100 transition-opacity">
                        </button>
                        <button class="aspect-square border border-transparent hover:border-moon-gold transition-colors overflow-hidden">
                            <img src="https://images.pexels.com/photos/15865612/pexels-photo-15865612/free-photo-of-brunette-in-abaya.jpeg?auto=compress&cs=tinysrgb&w=200" class="w-full h-full object-cover opacity-70 hover:opacity-100 transition-opacity">
                        </button>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="flex flex-col">
                    <h1 class="text-3xl md:text-5xl font-serif text-white mb-4">Royal Black Abaya</h1>
                    <div class="flex items-center space-x-4 mb-8">
                        <span class="text-2xl text-moon-gold font-bold">2,800 LE</span>
                        <span class="text-lg text-gray-500 line-through">3,500 LE</span>
                        <span class="text-xs font-bold bg-red-600 text-white px-2 py-1 uppercase tracking-widest">-20%</span>
                    </div>

                    <p class="text-gray-400 leading-relaxed mb-8 font-light">
                        Experience the epitome of elegance with our Royal Black Abaya. Crafted from the finest Medina silk, 
                        this piece features subtle hand-stitched detailing along the cuffs and hem. The fluid drape invites 
                        movement, while the deep, rich black hue commands attention. Perfect for evening occasions and formal gatherings.
                    </p>

                    <div class="space-y-6 mb-10">
                         <!-- Size Selector -->
                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="text-xs uppercase tracking-widest text-white">Size</label>
                                <a href="#" class="text-xs text-gray-500 underline hover:text-moon-gold">Size Guide</a>
                            </div>
                            <div class="flex space-x-3">
                                <button class="w-12 h-12 flex items-center justify-center border border-gray-700 text-gray-400 hover:border-moon-gold hover:text-moon-gold transition-all">S</button>
                                <button class="w-12 h-12 flex items-center justify-center border border-moon-gold bg-moon-gold text-moon-dark font-bold shadow-[0_0_10px_rgba(198,168,124,0.3)]">M</button>
                                <button class="w-12 h-12 flex items-center justify-center border border-gray-700 text-gray-400 hover:border-moon-gold hover:text-moon-gold transition-all">L</button>
                                <button class="w-12 h-12 flex items-center justify-center border border-gray-700 text-gray-400 hover:border-moon-gold hover:text-moon-gold transition-all">XL</button>
                            </div>
                        </div>

                        <!-- Quantity & Add -->
                        <div class="flex space-x-4">
                            <div class="flex items-center border border-gray-700">
                                <button class="px-3 py-4 text-gray-400 hover:text-white">-</button>
                                <input type="number" value="1" class="w-12 bg-transparent text-center text-white focus:outline-none appearance-none m-0">
                                <button class="px-3 py-4 text-gray-400 hover:text-white">+</button>
                            </div>
                            <button class="flex-1 bg-moon-gold text-moon-dark font-bold uppercase tracking-widest hover:bg-white transition-colors py-4">
                                Add to Bag
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 pt-8 space-y-4">
                        <div class="flex items-start space-x-3 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-moon-gold mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Premium Medina Silk</span>
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
                    <div class="group">
                        <div class="relative overflow-hidden aspect-[3/4] mb-3 bg-gray-800">
                            <img src="https://images.pexels.com/photos/28905393/pexels-photo-28905393/free-photo-of-elegant-woman-in-red-traditional-dress-in-marrakech.jpeg?auto=compress&cs=tinysrgb&w=600" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                        <h3 class="text-white text-sm font-serif group-hover:text-moon-gold cursor-pointer">Crimson Kaftan</h3>
                        <p class="text-gray-400 text-xs font-bold">4,200 LE</p>
                    </div>
                     <div class="group">
                        <div class="relative overflow-hidden aspect-[3/4] mb-3 bg-gray-800">
                            <img src="https://images.pexels.com/photos/1117272/pexels-photo-1117272.jpeg?auto=compress&cs=tinysrgb&w=600" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                        <h3 class="text-white text-sm font-serif group-hover:text-moon-gold cursor-pointer">Onyx Clutch</h3>
                        <p class="text-gray-400 text-xs font-bold">1,400 LE</p>
                    </div>
                     <div class="group">
                        <div class="relative overflow-hidden aspect-[3/4] mb-3 bg-gray-800">
                            <img src="https://images.pexels.com/photos/20344409/pexels-photo-20344409/free-photo-of-woman-in-long-coat-posing-in-passage.jpeg?auto=compress&cs=tinysrgb&w=600" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                        <h3 class="text-white text-sm font-serif group-hover:text-moon-gold cursor-pointer">Silk Robe</h3>
                        <p class="text-gray-400 text-xs font-bold">2,100 LE</p>
                    </div>
                     <div class="group">
                        <div class="relative overflow-hidden aspect-[3/4] mb-3 bg-gray-800">
                            <img src="https://images.pexels.com/photos/16848560/pexels-photo-16848560/free-photo-of-woman-in-dress-in-desert.jpeg?auto=compress&cs=tinysrgb&w=600" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                        <h3 class="text-white text-sm font-serif group-hover:text-moon-gold cursor-pointer">Desert Dress</h3>
                        <p class="text-gray-400 text-xs font-bold">3,100 LE</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
