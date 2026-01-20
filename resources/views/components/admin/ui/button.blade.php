@props([
    'type' => 'button', // button, submit, a
    'variant' => 'primary', // primary, secondary, danger, ghost
    'href' => null,
    'icon' => null,
    'fullWidth' => false,
])

@php
    $baseClasses = "inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:opacity-50 disabled:cursor-not-allowed";
    
    $variants = [
        'primary' => 'bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-500/20 focus:ring-blue-500',
        'secondary' => 'bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white border border-slate-700 focus:ring-slate-500',
        'danger' => 'bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 hover:text-rose-300 border border-rose-500/20 focus:ring-rose-500',
        'ghost' => 'text-slate-400 hover:text-white hover:bg-white/5 focus:ring-slate-500',
        'icon' => 'p-2 text-slate-400 hover:text-white hover:bg-white/5 rounded-lg'
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);

    if ($variant !== 'icon') {
        $classes .= ' px-4 py-2.5 text-sm';
    }

    if ($fullWidth) {
        $classes .= ' w-full';
    }
@endphp

@if($type === 'a' && $href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
