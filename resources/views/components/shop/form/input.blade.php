@props([
    'name' => null,
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
    'min' => null,
    'step' => null,
])

<div class="relative w-full">
    <input type="{{ $type }}" 
           @if($name) 
               name="{{ $name }}" 
               id="{{ $name }}"
           @endif
           value="{{ $value }}" 
           placeholder="{{ $placeholder }}"
           @if($min !== null) min="{{ $min }}" @endif
           @if($step !== null) step="{{ $step }}" @endif
           {{ $attributes->merge(['class' => 'w-full bg-transparent border border-neutral-300 px-4 py-2.5 text-sm text-charcoal placeholder-neutral-400 focus:border-charcoal outline-none transition-colors']) }}>
           
    @if($slot->isNotEmpty())
        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none">
            {{ $slot }}
        </div>
    @endif
</div>
