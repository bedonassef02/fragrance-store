@props([
    'color' => 'slate',
    'type' => null,
    'label' => null,
])

@php
    // Allow 'type' to override 'color' for semantic usage
    $finalColor = $type ?? $color;

    $colors = [
        'slate' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
        'default' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
        'gray' => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
        'blue' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
        'info' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
        'success' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
        'emerald' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
        'warning' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
        'amber' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
        'danger' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
        'rose' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
        'primary' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
        'orange' => 'bg-orange-500/10 text-orange-400 border-orange-500/20',
        'cyan' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
        'purple' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
        'teal' => 'bg-teal-500/10 text-teal-400 border-teal-500/20',
    ];

    $colorClass = $colors[$finalColor] ?? $colors['slate'];
    $classes = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide border " . $colorClass;
@endphp

<span class="{{ $classes }}">
    {{ $label ?? $slot }}
</span>
