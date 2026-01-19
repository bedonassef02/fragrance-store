@extends('layouts.app')

@section('title', 'Our Story | MOON')

@section('content')
    <!-- Hero -->
    <div class="relative h-[60vh] min-h-[400px] flex items-center justify-center bg-fixed bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1558171813-4c088753af8f?q=80&w=2000');">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative z-10 text-center max-w-4xl px-4">
            <h1 class="text-5xl md:text-7xl font-serif text-white mb-6">The Moon Legacy</h1>
            <p class="text-xl text-gray-200 font-light">Crafting elegance since 2014</p>
        </div>
    </div>

    <!-- Story Text -->
    <div class="py-24 bg-moon-dark">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <span class="text-moon-gold text-5xl font-serif block mb-8">"</span>
            <p class="text-2xl font-serif text-white leading-relaxed mb-12">
                Moon was born from a desire to bridge the gap between traditional Arabian heritage and contemporary fashion. We believe that true luxury lies in the details—the whisper of silk, the intricate dance of embroidery, and the confidence of the woman who wears it.
            </p>
            <div class="prose prose-invert mx-auto text-gray-400">
                <p>
                    Founded in the heart of Dubia, our atelier is home to skilled artisans who have dedicated their lives to the art of tailoring. Every piece involves countless hours of meticulous work, ensuring that it meets our exacting standards of quality and beauty.
                </p>
            </div>
        </div>
    </div>

    <!-- Stats/Values -->
    <div class="py-20 bg-black/50 border-y border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
            <div>
                <h3 class="text-moon-gold text-4xl font-serif mb-2">100%</h3>
                <p class="text-white font-bold uppercase tracking-widest text-sm mb-2">Handcrafted</p>
                <p class="text-gray-500 text-sm">Every stitch placed with intention.</p>
            </div>
            <div>
                <h3 class="text-moon-gold text-4xl font-serif mb-2">12+</h3>
                <p class="text-white font-bold uppercase tracking-widest text-sm mb-2">Years of Excellence</p>
                <p class="text-gray-500 text-sm">A decade of defining style.</p>
            </div>
            <div>
                <h3 class="text-moon-gold text-4xl font-serif mb-2">Global</h3>
                <p class="text-white font-bold uppercase tracking-widest text-sm mb-2">Shipping</p>
                <p class="text-gray-500 text-sm">Bringing luxury to your doorstep.</p>
            </div>
        </div>
    </div>
@endsection
