@extends('layouts.admin')

@section('title', 'Edit Category')
@section('header', 'Edit Category')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-panel p-6 rounded-2xl">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder-slate-600" required>
                    @error('name') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Slug -->
                <div>
                     <label class="block text-sm font-medium text-slate-400 mb-2">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder-slate-600">
                    @error('slug') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-700/50">
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-2.5 text-slate-400 hover:text-white font-medium transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/20">Update Category</button>
            </div>
        </form>
    </div>
</div>
@endsection
