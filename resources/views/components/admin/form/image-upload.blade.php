@props([
    'name' => 'image',
    'label' => 'Image',
    'existing' => null,
    'multiple' => false,
])

<div>
    <label class="block text-sm font-medium text-moon-gray-300 mb-2">{{ $label }}</label>
    
    @if($existing)
        <div class="mb-4">
            <div class="relative w-32 h-32 rounded-lg bg-white/5 border border-white/10 overflow-hidden group">
                <img src="{{ $existing }}" alt="Preview" class="w-full h-full object-cover">
                @if(!$multiple)
                     <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="text-xs text-white">Current</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="flex items-center gap-6">
        <div id="{{ $name }}-preview-container" class="hidden w-32 h-20 rounded-lg bg-white/5 border border-white/10 items-center justify-center overflow-hidden shrink-0">
             <!-- JS will populate this -->
        </div>

        <div class="flex-1">
             <label class="block w-full text-sm text-moon-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-moon-gold file:text-moon-dark hover:file:bg-yellow-400 transition-all cursor-pointer bg-black/20 rounded-xl border border-white/10 relative">
                <input type="file" 
                       name="{{ $name }}{{ $multiple ? '[]' : '' }}" 
                       id="{{ $name }}" 
                       accept="image/*"
                       {{ $multiple ? 'multiple' : '' }}
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                       onchange="previewImage(this, '{{ $name }}-preview-container')">
                <div class="px-4 py-3 flex items-center justify-between pointer-events-none">
                    <span class="text-moon-gray-400">Choose file...</span>
                    <span class="px-3 py-1 bg-moon-gold text-moon-dark rounded text-xs font-bold shadow-lg">Browse</span>
                </div>
            </label>
            <p class="text-xs text-moon-gray-500 mt-2">Recommended: JPG, PNG, WEBP.</p>
        </div>
    </div>
    @error($name) <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
</div>

<script>
    function previewImage(input, containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        
        if (input.files && input.files[0]) {
            container.classList.remove('hidden');
            container.classList.add('flex');
            
            const reader = new FileReader();
            reader.onload = function(e) {
                container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
