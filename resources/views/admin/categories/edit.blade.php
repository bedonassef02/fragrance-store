@extends('layouts.admin')

@section('title', 'Edit Category')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-white tracking-tight font-display">Edit Category</h1>
        <p class="text-moon-gray-400 mt-1">Update details for <span class="text-white font-medium">"{{ $category->name }}"</span></p>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('admin.categories.update', $category) }}" method="POST" class="max-w-xl mx-auto">
    @csrf
    @method('PUT')
    
    <div class="space-y-8">
        <!-- Main Details -->
        <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 font-display flex items-center gap-2">
                <svg class="w-5 h-5 text-moon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Category Information
            </h3>
            
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-moon-gray-300 mb-2">Category Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required placeholder="e.g. Hats"
                           class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                    @error('name') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-moon-gray-300 mb-2">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" placeholder="Auto-generated if empty"
                           class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                    @error('slug') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons (Bottom) -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-white/10">
            <a href="{{ route('admin.categories.index') }}" 
               class="px-6 py-3 text-sm text-moon-gray-400 hover:text-white transition-colors font-bold">
                Cancel
            </a>
            <button type="submit"
               class="px-8 py-3 bg-gradient-to-r from-moon-gold to-yellow-500 text-moon-dark font-bold text-sm rounded-xl hover:shadow-[0_0_20px_rgba(255,215,0,0.2)] transition-all transform hover:-translate-y-0.5">
                Update Category
            </button>
        </div>
    </div>
</form>
@endsection
