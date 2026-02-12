@props([
    'active' => false,
    'href' => '#',
])

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => 'px-4 py-2 rounded-lg border text-sm font-medium whitespace-nowrap transition-colors ' . ($active ? 'bg-moon-gold/10 text-moon-gold border-moon-gold/20' : 'bg-white/5 text-moon-gray-400 border-white/5 hover:bg-white/10 hover:text-white')]) }}>
    {{ $slot }}
</a>
