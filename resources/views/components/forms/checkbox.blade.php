@props(['name', 'value', 'label', 'id' => null, 'checked' => false])

<label for="{{ $id ?? $name.'-'.$value }}" class="flex items-center space-x-3 cursor-pointer group">
    <div class="relative flex items-center">
        <input id="{{ $id ?? $name.'-'.$value }}" type="checkbox" name="{{ $name }}" value="{{ $value }}" {{ $checked ? 'checked' : '' }} class="peer h-4 w-4 appearance-none border border-gray-600 rounded-sm checked:bg-moon-gold checked:border-moon-gold transition-all">
        <svg class="absolute w-3 h-3 text-black hidden peer-checked:block pointer-events-none left-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
    </div>
    <span class="text-sm text-gray-400 group-hover:text-moon-gold transition-colors">{{ $label }}</span>
</label>
