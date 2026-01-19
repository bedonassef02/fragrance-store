@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <header id="home" class="relative h-screen min-h-[600px] flex items-center justify-center overflow-hidden">
        <!-- Background Overlay -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-moon-dark"></div>
            <img src="https://images.unsplash.com/photo-1590735213920-68192a487bc2?q=80&w=2070&auto=format&fit=crop" 
                 alt="Luxury Abaya Background" 
                 class="w-full h-full object-cover object-center animate-kenburns opacity-60">
        </div>

        <!-- Content -->
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-16 sm:mt-0">
            <p class="text-moon-gold uppercase tracking-[0.4em] text-xs sm:text-sm font-light mb-6 animate-fadeInUp">The New Collection</p>
            <h1 class="text-5xl sm:text-6xl md:text-8xl font-serif text-white mb-8 leading-tight font-medium animate-fadeInUp delay-200">
                Elegance <span class="italic font-light text-moon-gold">Redefined</span>
            </h1>
            <p class="text-gray-300 text-lg sm:text-xl font-light mb-12 max-w-lg mx-auto leading-relaxed animate-fadeInUp delay-300">
                Discover the finest Arabian fashion where timeless tradition meets modern luxury.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center animate-fadeInUp delay-400">
                <a href="{{ route('shop') }}" class="group relative px-10 py-4 bg-moon-gold text-moon-dark font-serif tracking-widest uppercase hover:bg-white transition-all duration-300">
                    <span class="relative z-10 font-bold">Shop Abayas</span>
                </a>
                <a href="{{ route('collections') }}" class="group relative px-10 py-4 border border-white/30 text-white font-serif tracking-widest uppercase hover:border-moon-gold hover:text-moon-gold transition-all duration-300 backdrop-blur-sm">
                    <span class="relative z-10">Discover Bags</span>
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 hidden sm:block animate-bounce">
            <svg class="h-6 w-6 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>
    </header>

    <!-- Featured Section Preview (Shortened for Welcome) -->
    <section class="py-24 bg-moon-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-serif text-white mb-6">Discover Our World</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                <a href="{{ route('shop') }}?category=abayas" class="group relative h-96 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1583391733956-6c78276477e2?q=80&w=800" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Abayas">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                        <h3 class="text-2xl font-serif text-white border-b border-moon-gold pb-2 hover:text-moon-gold">Abayas</h3>
                    </div>
                </a>
                <a href="{{ route('collections') }}" class="group relative h-96 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1628045620958-8671607590d9?q=80&w=1000" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Collections">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                        <h3 class="text-2xl font-serif text-white border-b border-moon-gold pb-2 hover:text-moon-gold">Collections</h3>
                    </div>
                </a>
                <a href="{{ route('shop') }}?category=bags" class="group relative h-96 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?q=80&w=800" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Bags">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                        <h3 class="text-2xl font-serif text-white border-b border-moon-gold pb-2 hover:text-moon-gold">Bags</h3>
                    </div>
                </a>
            </div>
        </div>
    </section>
@endsection