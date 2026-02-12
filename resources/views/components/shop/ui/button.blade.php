@props([
    'variant' => 'primary', // primary, secondary, outline, link
    'type' => 'button',
    'href' => null,
    'icon' => null,
])

@php
    // Base classes
    $baseClasses = "inline-flex items-center justify-center transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed";
    
    // Variants
    $variants = [
        'primary' => "bg-charcoal text-cream uppercase tracking-[0.15em] text-xs font-medium px-8 py-4 hover:bg-mid-gray",
        'secondary' => "bg-transparent border border-charcoal text-charcoal uppercase tracking-[0.15em] text-xs font-medium px-8 py-4 hover:bg-charcoal hover:text-cream",
        'cream' => "bg-cream text-charcoal uppercase tracking-[0.15em] text-xs font-medium px-8 py-3 hover:bg-accent hover:text-cream",
        'outline' => "border border-neutral-300 text-neutral-600 font-medium text-sm px-5 py-3 hover:border-charcoal hover:text-charcoal",
        'link' => "text-charcoal uppercase tracking-[0.15em] text-xs font-medium border-b border-charcoal pb-1 hover:text-accent hover:border-accent p-0",
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) <span class="mr-2">{!! $icon !!}</span> @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) <span class="mr-2">{!! $icon !!}</span> @endif
        <span>{{ $slot }}</span>
    </button>
@endif
