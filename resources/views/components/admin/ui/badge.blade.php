@props([
    'color' => 'gray', // gray, red, yellow, green, blue, purple, pink
    'label' => '',
])

@php
    $colors = [
        'gray' => 'bg-white/5 text-moon-gray-400 border-white/10',
        'red' => 'bg-red-500/10 text-red-500 border-red-500/20',
        'yellow' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
        'green' => 'bg-green-500/10 text-green-500 border-green-500/20',
        'blue' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
        'purple' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
        'pink' => 'bg-pink-500/10 text-pink-500 border-pink-500/20',
        'gold' => 'bg-moon-gold/10 text-moon-gold border-moon-gold/20',
    ];
    
    $classes = "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border " . ($colors[$color] ?? $colors['gray']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot->isEmpty() ? $label : $slot }}
</span>
