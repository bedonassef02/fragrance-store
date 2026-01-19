@extends('layouts.app')

@section('title', 'Collections | MOON')

@section('content')
    <div class="pt-32 pb-16 bg-moon-dark min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-5xl font-serif text-white mb-12 text-center animate-fadeInUp">Curated Collections</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Ramadan Collection -->
                <div class="group relative h-[500px] overflow-hidden">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors z-10"></div>
                    <img src="https://images.unsplash.com/photo-1628045620958-8671607590d9?q=80&w=1000" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Ramadan">
                    <div class="absolute bottom-0 left-0 p-12 z-20">
                        <span class="text-moon-gold text-sm uppercase tracking-widest mb-2 block">Special Edition</span>
                        <h2 class="text-4xl font-serif text-white mb-4">Ramadan 2026</h2>
                        <a href="{{ route('shop') }}" class="text-white border-b border-white pb-1 hover:text-moon-gold hover:border-moon-gold transition-colors">Shop the Look</a>
                    </div>
                </div>

                <!-- Modern Minimalist -->
                <div class="group relative h-[500px] overflow-hidden">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors z-10"></div>
                    <img src="https://images.unsplash.com/photo-1590736969955-71cc94801759?q=80&w=800" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Minimalist">
                    <div class="absolute bottom-0 left-0 p-12 z-20">
                        <span class="text-moon-gold text-sm uppercase tracking-widest mb-2 block">New Season</span>
                        <h2 class="text-4xl font-serif text-white mb-4">Modern Minimalist</h2>
                        <a href="{{ route('shop') }}" class="text-white border-b border-white pb-1 hover:text-moon-gold hover:border-moon-gold transition-colors">Shop the Look</a>
                    </div>
                </div>

                <!-- Essentials -->
                <div class="group relative h-[500px] overflow-hidden md:col-span-2">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors z-10"></div>
                    <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?q=80&w=1600" class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-105" alt="Essentials">
                    <div class="absolute bottom-0 left-0 p-12 z-20 text-center w-full">
                        <span class="text-moon-gold text-sm uppercase tracking-widest mb-2 block">Everyday Luxury</span>
                        <h2 class="text-4xl font-serif text-white mb-4">The Essentials Edit</h2>
                        <a href="{{ route('shop') }}" class="inline-block bg-white text-black px-8 py-3 uppercase tracking-widest text-xs font-bold hover:bg-moon-gold hover:text-white transition-colors">Explore All</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
