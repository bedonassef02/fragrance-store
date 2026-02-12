@props([
    'title',
    'subtitle' => null,
    'ctaText' => null,
    'ctaHref' => null,
    'centered' => true,
    'size' => 'default', // default, large, small
])

@php
    $alignmentClass = $centered ? 'text-center' : 'text-center md:text-left';
    
    $titleSizes = [
        'default' => 'text-3xl md:text-4xl',
        'large' => 'text-4xl sm:text-5xl lg:text-6xl xl:text-7xl leading-[1.1]',
        'small' => 'text-2xl md:text-3xl',
    ];
    
    $titleClass = $titleSizes[$size] ?? $titleSizes['default'];
@endphp

<div {{ $attributes->merge(['class' => $alignmentClass . ' mb-12']) }}>
    @if($subtitle)
        <p class="text-accent uppercase tracking-[0.3em] text-xs font-medium mb-3 md:mb-4 animate-fadeInUp">{{ $subtitle }}</p>
    @endif
    
    <h2 class="{{ $titleClass }} font-serif text-charcoal mb-4 md:mb-6 animate-fadeInUp" style="animation-delay: 0.1s">
        {{ $title }}
    </h2>
    
    @if($slot->isNotEmpty())
        <div class="text-neutral-600 text-lg font-light leading-relaxed mb-8 max-w-2xl mx-auto animate-fadeInUp" style="animation-delay: 0.2s">
            {{ $slot }}
        </div>
    @endif
    
    @if($ctaText && $ctaHref)
        <a href="{{ $ctaHref }}" 
           class="text-charcoal uppercase tracking-[0.15em] text-xs font-medium border-b border-charcoal pb-1 hover:text-accent hover:border-accent transition-colors animate-fadeInUp" 
           style="animation-delay: 0.3s">
            {{ $ctaText }}
        </a>
    @endif
</div>
