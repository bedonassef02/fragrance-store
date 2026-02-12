@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, danger, ghost
    'href' => null,
    'icon' => null,
])

@php
    $baseClasses = "inline-flex items-center justify-center gap-2 px-4 py-2 font-bold text-sm rounded-lg transition-all transform hover:-translate-y-0.5 whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed";
    
    $variants = [
        'primary' => "bg-gradient-to-r from-moon-gold to-yellow-500 text-moon-dark hover:shadow-[0_0_15px_rgba(255,215,0,0.3)]",
        'secondary' => "bg-white/5 text-moon-gray-400 border border-white/10 hover:bg-white/10 hover:text-white",
        'danger' => "bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500 hover:text-white",
        'ghost' => "text-moon-gray-400 hover:text-white hover:bg-white/5",
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) {!! $icon !!} @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) {!! $icon !!} @endif
        <span>{{ $slot }}</span>
    </button>
@endif
