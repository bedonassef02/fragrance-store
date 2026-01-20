@extends('layouts.admin')

@section('title', 'Create Collection')
@section('header', 'Create Collection')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-panel p-6 rounded-2xl">
        <form action="{{ route('admin.collections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-400 mb-2">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder-slate-600" placeholder="e.g. Summer Essentials" required>
                    @error('title') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Slug -->
                <div>
                     <label class="block text-sm font-medium text-slate-400 mb-2">Slug (Optional)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder-slate-600" placeholder="Auto-generated if empty">
                    @error('slug') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                 <!-- Sort Order -->
                 <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Sort Order</label>
                   <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder-slate-600">
                   @error('sort_order') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
               </div>

                <!-- Subtitle -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-400 mb-2">Subtitle</label>
                    <textarea name="subtitle" rows="2" class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder-slate-600">{{ old('subtitle') }}</textarea>
                    @error('subtitle') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Image -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-400 mb-2">Cover Image</label>
                    <input type="file" name="image" class="block w-full text-sm text-slate-400
                        file:mr-4 file:py-2.5 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-600/10 file:text-blue-400
                        hover:file:bg-blue-600/20
                    " accept="image/*">
                    <p class="mt-1 text-xs text-slate-500">Recommended size: 1200x600px</p>
                    @error('image') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Layout Class -->
                 <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Layout Class</label>
                   <input type="text" name="layout_class" value="{{ old('layout_class') }}" class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder-slate-600" placeholder="e.g. col-span-2">
                   @error('layout_class') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
               </div>
               
               <!-- CTA Text -->
               <div>
                  <label class="block text-sm font-medium text-slate-400 mb-2">CTA Text</label>
                 <input type="text" name="cta_text" value="{{ old('cta_text') }}" class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder-slate-600" placeholder="e.g. Shop Now">
                 @error('cta_text') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
             </div>

            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-700/50">
                <a href="{{ route('admin.collections.index') }}" class="px-6 py-2.5 text-slate-400 hover:text-white font-medium transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/20">Create Collection</button>
            </div>
        </form>
    </div>
</div>
@endsection
