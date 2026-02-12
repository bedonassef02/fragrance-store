@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
    'placeholder' => 'Select an option',
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-moon-gray-300 mb-2">{{ $label }}</label>
    @endif
    
    <select name="{{ $name }}" 
            id="{{ $name }}" 
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full bg-neutral-900 border border-white/10 rounded-xl px-4 py-3 text-moon-gray-300 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all']) }}>
            
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ (string)old($name, $selected) === (string)$value ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
        
        {{ $slot }}
    </select>
           
    @error($name) 
        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> 
    @enderror
</div>
