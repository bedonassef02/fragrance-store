@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'glass-panel rounded-2xl ' . $class]) }}>
    {{ $slot }}
</div>
