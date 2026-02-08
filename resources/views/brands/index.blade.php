@extends('layouts.app')

@section('title', 'Local Brands | MOON')

@section('content')
    <div class="pt-32 pb-16 bg-cream min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-accent uppercase tracking-[0.3em] text-xs font-medium mb-4">Our Partners</p>
                <h1 class="text-4xl md:text-5xl font-serif text-charcoal animate-fadeInUp">Local Brands</h1>
                <p class="text-neutral-500 mt-4 max-w-xl mx-auto">Discover exceptional fragrances from Egypt's finest local perfumers.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($brands as $brand)
                <a href="{{ route('shop', ['brand' => $brand->slug]) }}" class="group block bg-white rounded-sm overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="aspect-square overflow-hidden bg-neutral-100">
                        @if($brand->image)
                        <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-4xl font-serif text-neutral-300">{{ substr($brand->name, 0, 1) }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="font-serif text-lg text-charcoal group-hover:text-accent transition-colors">{{ $brand->name }}</h3>
                        <p class="text-neutral-500 text-xs mt-1">{{ $brand->products_count }} {{ Str::plural('product', $brand->products_count) }}</p>
                    </div>
                </a>
                @endforeach
            </div>

            @if($brands->isEmpty())
            <div class="text-center py-20">
                <p class="text-neutral-500">No local brands available at the moment.</p>
            </div>
            @endif

            <!-- Pagination -->
            @if($brands->hasPages())
            <div class="mt-12">
                {{ $brands->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
