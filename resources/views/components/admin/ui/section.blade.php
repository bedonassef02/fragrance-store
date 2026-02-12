@props([
    'title' => null,
    'icon' => null,
    'footer' => null,
])

<div {{ $attributes->merge(['class' => 'bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-6 md:p-8 shadow-2xl']) }}>
    @if($title)
        <h3 class="text-xl font-bold text-white mb-6 font-display flex items-center gap-2">
            @if($icon)
                <div class="text-moon-gold">
                    {!! $icon !!}
                </div>
            @endif
            {{ $title }}
        </h3>
    @endif

    <div class="space-y-6">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="mt-8 pt-6 border-t border-white/10">
            {{ $footer }}
        </div>
    @endif
</div>
