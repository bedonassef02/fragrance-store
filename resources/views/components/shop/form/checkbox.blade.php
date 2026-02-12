@props([
    'name',
    'value',
    'label',
    'checked' => false,
])

<label {{ $attributes->merge(['class' => 'flex items-center cursor-pointer group']) }}>
    <input type="checkbox" 
           name="{{ $name }}" 
           value="{{ $value }}" 
           {{ $checked ? 'checked' : '' }}
           class="w-4 h-4 rounded-sm border-neutral-300 text-charcoal focus:ring-charcoal focus:ring-offset-0 cursor-pointer">
    <span class="ml-3 text-sm text-neutral-600 group-hover:text-charcoal transition-colors">{{ $label }}</span>
</label>
