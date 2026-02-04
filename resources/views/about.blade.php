@extends('layouts.app')

@section('title', 'Our Story | MOON')

@section('content')
    <!-- Hero -->
    <div class="relative min-h-[60vh] flex items-center justify-center bg-neutral-100 pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center py-16">
                <div class="text-center lg:text-left">
                    <p class="text-accent uppercase tracking-[0.3em] text-xs font-medium mb-6">Our Story</p>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif text-charcoal mb-6 leading-tight">The Moon Legacy</h1>
                    <p class="text-neutral-600 text-lg leading-relaxed">Crafting distinctive fragrances that bridge tradition and modernity since 2014.</p>
                </div>
                <div class="aspect-square overflow-hidden bg-neutral-200">
                    <img src="https://images.unsplash.com/photo-1558171813-4c088753af8f?q=80&w=800" alt="Our Story" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>

    <!-- Story Text -->
    <section class="section-padding bg-cream">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <span class="text-accent text-5xl font-serif block mb-6">"</span>
            <p class="text-2xl font-serif text-charcoal leading-relaxed mb-10">
                Moon was born from a desire to capture the essence of moments—memories bottled with care, meant to evoke emotion and leave a lasting impression.
            </p>
            <div class="text-neutral-600 leading-relaxed space-y-4">
                <p>
                    Founded in Egypt, our atelier is home to master perfumers who have dedicated their craft to creating scents that transcend time. Every fragrance is a journey, carefully composed with the finest ingredients sourced from around the world.
                </p>
                <p>
                    We believe that true luxury lies in the details—the perfect balance of notes, the longevity of a scent, and the confidence it brings to those who wear it.
                </p>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="section-padding bg-charcoal text-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
            <div>
                <h3 class="text-accent text-4xl font-serif mb-3">100%</h3>
                <p class="text-cream font-medium uppercase tracking-[0.15em] text-sm mb-2">Authentic</p>
                <p class="text-neutral-400 text-sm">Every scent crafted with genuine passion.</p>
            </div>
            <div>
                <h3 class="text-accent text-4xl font-serif mb-3">10+</h3>
                <p class="text-cream font-medium uppercase tracking-[0.15em] text-sm mb-2">Years of Excellence</p>
                <p class="text-neutral-400 text-sm">A decade of creating memorable fragrances.</p>
            </div>
            <div>
                <h3 class="text-accent text-4xl font-serif mb-3">Egypt</h3>
                <p class="text-cream font-medium uppercase tracking-[0.15em] text-sm mb-2">Nationwide Delivery</p>
                <p class="text-neutral-400 text-sm">Bringing luxury scents to your doorstep.</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="section-padding bg-cream">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <p class="text-accent uppercase tracking-[0.3em] text-xs font-medium mb-4">Discover</p>
            <h2 class="text-3xl md:text-4xl font-serif text-charcoal mb-6">Find Your Signature Scent</h2>
            <p class="text-neutral-600 mb-8">Explore our curated collection of fragrances designed to tell your unique story.</p>
            <a href="{{ route('shop') }}" class="btn-primary">Shop Collection</a>
        </div>
    </section>
@endsection
