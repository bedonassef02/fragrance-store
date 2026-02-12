@props([
    'name' => 'search',
    'placeholder' => 'Search...',
    'action' => null,
])

<div class="relative w-full md:w-96">
    <form action="{{ $action ?? url()->current() }}" method="GET">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-moon-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input type="text" name="{{ $name }}" value="{{ request($name) }}" placeholder="{{ $placeholder }}" 
               class="w-full bg-black/20 border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
        
        <!-- Preserve other query params -->
        @foreach(request()->except([$name, 'page']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
</div>
