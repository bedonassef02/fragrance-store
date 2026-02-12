@props([
    'title' => 'No items found',
    'message' => 'Try adjusting your search or filter to find what you are looking for.',
    'action' => null,
    'actionLabel' => null,
])

<tr>
    <td colspan="100%" class="px-6 py-32 text-center">
        <div class="flex flex-col items-center justify-center">
            <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mb-6 ring-1 ring-white/10">
                <svg class="w-10 h-10 text-moon-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2 font-display">{{ $title }}</h3>
            <p class="text-moon-gray-400 mb-8 max-w-sm mx-auto">{{ $message }}</p>
            
            @if($action && $actionLabel)
                <a href="{{ $action }}" 
                   class="px-8 py-3 bg-moon-gold text-moon-dark font-bold rounded-xl hover:bg-yellow-500 transition-all shadow-lg hover:shadow-moon-gold/20">
                    {{ $actionLabel }}
                </a>
            @endif
        </div>
    </td>
</tr>
