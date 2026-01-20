@props(['name', 'idPrefix'])

<div class="flex flex-row-reverse justify-end gap-2 group">
    @for($i = 5; $i >= 1; $i--)
    <input type="radio" name="{{ $name }}" id="{{ $idPrefix }}-{{ $i }}" value="{{ $i }}" class="peer hidden" required>
    <label for="{{ $idPrefix }}-{{ $i }}" class="text-gray-600 cursor-pointer peer-checked:text-moon-gold hover:text-moon-gold peer-hover:text-moon-gold transition-colors">
        <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
    </label>
    @endfor
</div>
