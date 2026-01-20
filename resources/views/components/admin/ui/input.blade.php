@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-400 mb-2">
            {{ $label }}
            @if($required) <span class="text-red-400">*</span> @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        value="{{ old($name, $value) }}" 
        {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder-slate-600 disabled:opacity-50 disabled:cursor-not-allowed {{ $errors->has($name) ? 'border-red-500/50 focus:ring-red-500' : '' }}" 
        placeholder="{{ $placeholder }}"
    >

    @error($name)
        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
