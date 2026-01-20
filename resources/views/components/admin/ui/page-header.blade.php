@props([
    'title',
    'description' => null,
])

<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-3xl font-bold text-white tracking-tight">{{ $title }}</h2>
        @if($description)
            <p class="text-slate-400 mt-1">{{ $description }}</p>
        @endif
    </div>
    
    @if(isset($actions))
        <div class="flex items-center gap-3">
            {{ $actions }}
        </div>
    @endif
</div>
