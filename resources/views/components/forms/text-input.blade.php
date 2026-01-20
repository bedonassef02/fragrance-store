@props([
    'disabled' => false,
    'type' => 'text',
])

<input type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full bg-gray-900 border border-gray-700 text-white p-4 focus:border-moon-gold focus:ring-0 outline-none transition-colors rounded-sm placeholder-gray-500']) !!}>
