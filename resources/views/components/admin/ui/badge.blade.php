@props([
    'color' => 'slate', // slate, mobile, success, warning, danger
    'label',
])

@php
    $colors = [
        'slate' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
        'blue' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
        'success' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
        'emrald' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
        'warning' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
        'amber' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
        'danger' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
        'rose' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
    ];

    $classes = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide border " . ($colors[$color] ?? $colors['slate']);
@endphp

<span class="{{ $classes }}">
    {{ $label }}
</span>
