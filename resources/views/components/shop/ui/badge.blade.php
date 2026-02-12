@props([
    'label',
    'color' => 'charcoal', // charcoal, accent, neutral
    'position' => 'absolute top-3 left-3', // Default positioning for card badges
])

@php
    $colors = [
        'charcoal' => 'bg-charcoal text-cream',
        'accent' => 'bg-accent text-white', // Assuming accent color works with white text
        'neutral' => 'bg-neutral-500 text-white',
        'outline' => 'bg-transparent border border-neutral-300 text-neutral-600',
    ];
    
    $baseStyle = "text-[10px] font-medium px-2.5 py-1 uppercase tracking-wider";
    $classes = $position . ' ' . ($colors[$color] ?? $colors['charcoal']) . ' ' . $baseStyle;
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $label }}
</span>
