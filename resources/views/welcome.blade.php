@extends('layouts.app')

@section('content')
    <x-seo.schema-org type="organization" />
    <x-seo.schema-org type="website" />
    
    <!-- Hero Section -->
    <header class="relative min-h-[90vh] flex items-center justify-center bg-cream pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left order-2 lg:order-1">
                    <p class="text-accent uppercase tracking-[0.3em] text-xs font-medium mb-6 animate-fadeInUp">{{ $hero['subtitle'] }}</p>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-serif text-charcoal mb-6 leading-[1.1] animate-fadeInUp" style="animation-delay: 0.1s">
                        {{ $hero['title'] }}
                    </h1>
                    <p class="text-neutral-600 text-lg font-light mb-10 max-w-md mx-auto lg:mx-0 leading-relaxed animate-fadeInUp" style="animation-delay: 0.2s">
                        {{ $hero['description'] }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start animate-fadeInUp" style="animation-delay: 0.3s">
                        <x-shop.ui.button :href="route($hero['primary_cta']['route'])" variant="primary">
                            {{ $hero['primary_cta']['text'] }}
                        </x-shop.ui.button>
                        <x-shop.ui.button :href="route($hero['secondary_cta']['route'])" variant="secondary">
                            {{ $hero['secondary_cta']['text'] }}
                        </x-shop.ui.button>
                    </div>
                </div>
                
                <!-- Hero Image -->
                <div class="order-1 lg:order-2 relative">
                    <div class="aspect-[3/4] overflow-hidden rounded-sm">
                        <img src="{{ $hero['image'] }}" 
                             alt="Featured Fragrance" 
                             class="w-full h-full object-cover">
                    </div>
                    <!-- Decorative Element -->
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 border border-accent/30 rounded-sm hidden lg:block"></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Picked For You Section -->
    @if(isset($pickedForYou) && $pickedForYou->isNotEmpty())
    <section class="section-padding bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-shop.ui.section-header 
                title="Picked For You" 
                subtitle="Based on Your History" 
            />
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                @foreach($pickedForYou as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Collections -->
    <section class="section-padding bg-neutral-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-shop.ui.section-header 
                title="Our Collections" 
                subtitle="Explore" 
            />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($collections as $item)
                <a href="{{ route('shop', ['collection' => $item->slug]) }}" class="group relative aspect-[3/4] overflow-hidden bg-neutral-200">
                    <img src="{{ $item['image'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $item['title'] }}">
                    <div class="absolute inset-0 bg-charcoal/20 group-hover:bg-charcoal/30 transition-colors duration-300"></div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-6">
                        <h3 class="text-xl md:text-2xl font-serif text-white text-center">{{ $item['title'] }}</h3>
                        <span class="mt-3 text-xs uppercase tracking-[0.2em] text-white/80 border-b border-white/50 pb-1 group-hover:border-white transition-colors">Shop Now</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Local Brands Section -->
    @if(isset($brands) && $brands->isNotEmpty())
    <section class="section-padding bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-shop.ui.section-header 
                title="Local Brands" 
                subtitle="Our Partners" 
                cta-text="View All" 
                :cta-href="route('brands.index')" 
                :centered="false"
                class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12 !text-left"
            />
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($brands as $brand)
                <a href="{{ route('shop', ['brand' => $brand->slug]) }}" class="group block bg-white rounded-sm overflow-hidden shadow-sm hover:shadow-md transition-all">
                    <div class="aspect-square overflow-hidden bg-neutral-100">
                        @if($brand->image)
                        <img src="{{ $brand->image }}" alt="{{ $brand->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-5xl font-serif text-neutral-300">{{ substr($brand->name, 0, 1) }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="font-serif text-lg text-charcoal group-hover:text-accent transition-colors">{{ $brand->name }}</h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Trending Section -->
    @if($trending->isNotEmpty())
    <section class="section-padding bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-shop.ui.section-header 
                title="Trending Now" 
                subtitle="Most Loved" 
                cta-text="View All" 
                :cta-href="route('shop')" 
                :centered="false"
                class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12 !text-left"
            />
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                @foreach($trending as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Brand Story Teaser -->
    <section class="section-padding bg-charcoal text-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <div class="aspect-square overflow-hidden">
                    <img src="{{ asset('storage/images/about-story.jpg') }}" alt="Our Story" class="w-full h-full object-cover" onerror="this.style.display='none'">
                </div>
                <div class="text-center lg:text-left">
                    <p class="text-accent uppercase tracking-[0.3em] text-xs font-medium mb-6">Our Story</p>
                    <h2 class="text-3xl md:text-4xl font-serif mb-6">Crafted with Passion</h2>
                    <p class="text-neutral-300 text-lg font-light leading-relaxed mb-8">
                        Every fragrance tells a story. Ours begins with a passion for exceptional scents and a dedication to craftsmanship that honors tradition while embracing innovation.
                    </p>
                    <x-shop.ui.button :href="route('about')" variant="link" icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>'>
                        Discover More
                    </x-shop.ui.button>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    @if($featured->isNotEmpty())
    <section class="section-padding bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-shop.ui.section-header 
                title="Curated For You" 
                subtitle="Editor's Choice" 
            />
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                @foreach($featured as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection