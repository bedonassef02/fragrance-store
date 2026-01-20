@props([
    'name',
    'label' => null,
    'required' => false,
    'accept' => 'image/*',
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-400 mb-2">
            {{ $label }}
            @if($required) <span class="text-red-400">*</span> @endif
        </label>
    @endif

    <input 
        type="file" 
        name="{{ $name }}" 
        id="{{ $name }}"
        accept="{{ $accept }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'block w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600/10 file:text-blue-400 hover:file:bg-blue-600/20 transition-colors']) }}
    >

    @error($name)
        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
