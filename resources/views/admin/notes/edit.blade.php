@extends('layouts.admin')

@section('title', 'Edit Note')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-white tracking-tight font-display">Edit Note</h1>
        <p class="text-moon-gray-400 mt-1">Update details for <span class="text-white font-medium">"{{ $note->name }}"</span></p>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('admin.notes.update', $note) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto">
    @csrf
    @method('PUT')
    
    <div class="space-y-8">
        <!-- Main Details -->
        <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 font-display flex items-center gap-2">
                <svg class="w-5 h-5 text-moon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                Note details
            </h3>
            
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-moon-gray-300 mb-2">Note Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $note->name) }}" required placeholder="e.g. Bergamot"
                           class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                    @error('name') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-moon-gray-300 mb-2">Default Category</label>
                    <select name="type" id="type" required
                            class="w-full bg-neutral-900 border border-white/10 rounded-xl px-4 py-3 text-moon-gray-300 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                        <option value="general" {{ old('type', $note->type) == 'general' ? 'selected' : '' }}>General / Unclassified</option>
                        <option value="top" {{ old('type', $note->type) == 'top' ? 'selected' : '' }}>Top Note</option>
                        <option value="heart" {{ old('type', $note->type) == 'heart' ? 'selected' : '' }}>Heart Note</option>
                        <option value="base" {{ old('type', $note->type) == 'base' ? 'selected' : '' }}>Base Note</option>
                    </select>
                    <p class="text-xs text-moon-gray-500 mt-2">This is the default classification. It can be overridden per product.</p>
                    @error('type') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-moon-gray-300 mb-2">Icon / Image</label>
                    <div class="flex items-center gap-6">
                        <div id="image-preview" class="w-20 h-20 rounded-full bg-white/5 border border-white/10 flex items-center justify-center overflow-hidden shrink-0">
                            @if($note->image)
                                <img src="{{ Storage::url($note->image) }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-moon-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="block w-full text-sm text-moon-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-moon-gold file:text-moon-dark hover:file:bg-yellow-400 transition-all cursor-pointer bg-black/20 rounded-xl border border-white/10"
                                   onchange="document.getElementById('image-preview').innerHTML = `<img src='${window.URL.createObjectURL(this.files[0])}' class='w-full h-full object-cover'>`">
                        </div>
                    </div>
                    @error('image') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons (Bottom) -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-white/10">
            <a href="{{ route('admin.notes.index') }}" 
               class="px-6 py-3 text-sm text-moon-gray-400 hover:text-white transition-colors font-bold">
                Cancel
            </a>
            <button type="submit"
               class="px-8 py-3 bg-gradient-to-r from-moon-gold to-yellow-500 text-moon-dark font-bold text-sm rounded-xl hover:shadow-[0_0_20px_rgba(255,215,0,0.2)] transition-all transform hover:-translate-y-0.5">
                Update Note
            </button>
        </div>
    </div>
</form>
@endsection
