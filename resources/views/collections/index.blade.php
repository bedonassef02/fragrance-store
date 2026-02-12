@extends('layouts.app')

@section('title', 'Collections | MOON')

@section('content')
    <div class="pt-32 pb-16 bg-cream min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-shop.ui.section-header 
                title="Curated Collections" 
                subtitle="Explore" 
            />
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($collections as $collection)
                <div class="group relative h-[500px] overflow-hidden {{ $collection['class'] }}">
                    <div class="absolute inset-0 bg-charcoal/20 group-hover:bg-charcoal/40 transition-colors z-10"></div>
                    <img src="{{ $collection['image'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $collection['title'] }}">
                    <div class="absolute bottom-0 left-0 p-12 z-20 {{ $collection['class'] ? 'text-center w-full' : '' }}">
                        <span class="text-accent text-sm uppercase tracking-widest mb-2 block">{{ $collection['subtitle'] }}</span>
                        <h2 class="text-4xl font-serif text-white mb-4">{{ $collection['title'] }}</h2>
                        <x-shop.ui.button :href="route('shop', ['collection' => $collection->slug])" variant="cream">
                            {{ $collection['cta_text'] }}
                        </x-shop.ui.button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
