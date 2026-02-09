@extends('layouts.admin')

@section('title', 'Edit Brand')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-white tracking-tight font-display">Edit Brand</h1>
        <p class="text-moon-gray-400 mt-1">Update details for <span class="text-white font-medium">"{{ $brand->name }}"</span></p>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="max-w-3xl mx-auto">
    @csrf
    @method('PUT')
    
    <div class="space-y-8">
        <!-- Main Details -->
        <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 font-display flex items-center gap-2">
                <svg class="w-5 h-5 text-moon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                Brand Information
            </h3>
            
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-moon-gray-300 mb-2">Brand Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $brand->name) }}" required placeholder="e.g. Dior"
                           class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                    @error('name') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-moon-gray-300 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" placeholder="Brand history and philosophy..."
                              class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all resize-none">{{ old('description', $brand->description) }}</textarea>
                    @error('description') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Logo -->
                <div>
                    <label class="block text-sm font-medium text-moon-gray-300 mb-2">Brand Logo</label>
                    <div class="flex items-center gap-6">
                        <div id="image-preview" class="w-24 h-24 rounded-full bg-white/5 border border-white/10 flex items-center justify-center overflow-hidden shrink-0">
                            @if($brand->image)
                                <img src="{{ $brand->image }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-moon-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="block w-full text-sm text-moon-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-moon-gold file:text-moon-dark hover:file:bg-yellow-400 transition-all cursor-pointer bg-black/20 rounded-xl border border-white/10"
                                   onchange="document.getElementById('image-preview').innerHTML = `<img src='${window.URL.createObjectURL(this.files[0])}' class='w-full h-full object-cover'>`">
                            <p class="text-xs text-moon-gray-500 mt-2">Recommended: Square image, 500x500px or larger.</p>
                        </div>
                    </div>
                    @error('image') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Luxury Switch -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-white/5 bg-white/[0.02] hover:bg-white/5 cursor-pointer transition-all group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="is_luxury" value="1" {{ old('is_luxury', $brand->is_luxury) ? 'checked' : '' }} 
                                   class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-white/10 bg-black/40 checked:border-moon-gold checked:bg-moon-gold transition-all">
                            <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 opacity-0 peer-checked:opacity-100 text-moon-dark transition-opacity" fill="none" viewBox="0 0 14 14" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 7l3 3 5-5"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-white block">Luxury Brand</span>
                            <span class="text-xs text-moon-gray-500 block">Marks this brand as a high-end luxury house.</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-4 rounded-xl border border-white/5 bg-white/[0.02] hover:bg-white/5 cursor-pointer transition-all group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="is_local" value="1" {{ old('is_local', $brand->is_local) ? 'checked' : '' }} 
                                   class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-white/10 bg-black/40 checked:border-moon-gold checked:bg-moon-gold transition-all">
                            <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 opacity-0 peer-checked:opacity-100 text-moon-dark transition-opacity" fill="none" viewBox="0 0 14 14" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 7l3 3 5-5"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-white block">Local Brand</span>
                            <span class="text-xs text-moon-gray-500 block">Marks this brand as a local Egyptian brand.</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons (Bottom) -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-white/10">
            <a href="{{ route('admin.brands.index') }}" 
               class="px-6 py-3 text-sm text-moon-gray-400 hover:text-white transition-colors font-bold">
                Cancel
            </a>
            <button type="submit"
               class="px-8 py-3 bg-gradient-to-r from-moon-gold to-yellow-500 text-moon-dark font-bold text-sm rounded-xl hover:shadow-[0_0_20px_rgba(255,215,0,0.2)] transition-all transform hover:-translate-y-0.5">
                Update Brand
            </button>
        </div>
    </div>
</form>
@endsection
