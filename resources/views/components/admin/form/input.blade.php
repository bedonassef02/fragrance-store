@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => '',
    'required' => false,
    'placeholder' => '',
    'error' => null,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-moon-gray-300 mb-2">{{ $label }}</label>
    @endif
    
    <input type="{{ $type }}" 
           name="{{ $name }}" 
           id="{{ $name }}" 
           value="{{ old($name, $value) }}" 
           {{ $required ? 'required' : '' }}
           placeholder="{{ $placeholder }}"
           {{ $attributes->merge(['class' => 'w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all']) }}>
           
    @error($name) 
        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> 
    @enderror
</div>
