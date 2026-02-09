@extends('layouts.admin')

@section('title', 'Edit Collection')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-white tracking-tight font-display">Edit Collection</h1>
        <p class="text-moon-gray-400 mt-1">Update details for <span class="text-white font-medium">"{{ $collection->title }}"</span></p>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('admin.collections.update', $collection) }}" method="POST" enctype="multipart/form-data" class="max-w-3xl mx-auto">
    @csrf
    @method('PUT')
    
    <div class="space-y-8">
        <!-- Main Details -->
        <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 font-display flex items-center gap-2">
                <svg class="w-5 h-5 text-moon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Collection Details
            </h3>
            
            <div class="space-y-6">
                <!-- Title & Slug -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-moon-gray-300 mb-2">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $collection->title) }}" required placeholder="e.g. Summer Essentials"
                               class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                        @error('title') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="slug" class="block text-sm font-medium text-moon-gray-300 mb-2">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $collection->slug) }}" placeholder="Auto-generated if empty"
                               class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                        @error('slug') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Subtitle -->
                <div>
                    <label for="subtitle" class="block text-sm font-medium text-moon-gray-300 mb-2">Subtitle</label>
                    <textarea name="subtitle" id="subtitle" rows="2" placeholder="Brief description of this collection..."
                              class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all resize-none">{{ old('subtitle', $collection->subtitle) }}</textarea>
                    @error('subtitle') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Cover Image -->
                <div>
                    <label class="block text-sm font-medium text-moon-gray-300 mb-2">Cover Image</label>
                    <div class="flex items-center gap-6">
                         <div id="image-preview" class="w-32 h-20 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center overflow-hidden shrink-0">
                            @if($collection->image)
                                <img src="{{ $collection->image }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-moon-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="block w-full text-sm text-moon-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-moon-gold file:text-moon-dark hover:file:bg-yellow-400 transition-all cursor-pointer bg-black/20 rounded-xl border border-white/10"
                                   onchange="document.getElementById('image-preview').innerHTML = `<img src='${window.URL.createObjectURL(this.files[0])}' class='w-full h-full object-cover'>`">
                            <p class="text-xs text-moon-gray-500 mt-2">Recommended: 1200x600px landscape image.</p>
                        </div>
                    </div>
                    @error('image') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Additional Settings -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-moon-gray-300 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $collection->sort_order) }}"
                               class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                    </div>
                    <div>
                        <label for="layout_class" class="block text-sm font-medium text-moon-gray-300 mb-2">Layout Class</label>
                        <input type="text" name="layout_class" id="layout_class" value="{{ old('layout_class', $collection->layout_class) }}" placeholder="e.g. col-span-2"
                               class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                    </div>
                    <div>
                        <label for="cta_text" class="block text-sm font-medium text-moon-gray-300 mb-2">CTA Text</label>
                        <input type="text" name="cta_text" id="cta_text" value="{{ old('cta_text', $collection->cta_text) }}" placeholder="Shop Now"
                               class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                    </div>
                </div>

            </div>
        </div>

        <!-- Action Buttons (Bottom) -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-white/10">
            <a href="{{ route('admin.collections.index') }}" 
               class="px-6 py-3 text-sm text-moon-gray-400 hover:text-white transition-colors font-bold">
                Cancel
            </a>
            <button type="submit"
               class="px-8 py-3 bg-gradient-to-r from-moon-gold to-yellow-500 text-moon-dark font-bold text-sm rounded-xl hover:shadow-[0_0_20px_rgba(255,215,0,0.2)] transition-all transform hover:-translate-y-0.5">
                Update Collection
            </button>
        </div>
    </div>
</form>
@endsection
