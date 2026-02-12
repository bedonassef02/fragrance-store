@props([
    'title',
    'subtitle' => null,
])

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-white tracking-tight font-display">{{ $title }}</h1>
        @if($subtitle)
            <p class="text-moon-gray-400 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    
    @if($slot->isNotEmpty())
        <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
            {{ $slot }}
        </div>
    @endif
</div>
