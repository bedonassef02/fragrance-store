@extends('layouts.admin')

@section('title', 'Categories')

@section('header')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-white tracking-tight font-display">Categories</h1>
        <p class="text-moon-gray-400 mt-1">Manage product categories.</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Toolbar -->
    <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/5 rounded-2xl p-4 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <form action="{{ route('admin.categories.index') }}" method="GET">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-moon-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." 
                       class="w-full bg-black/20 border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
            </form>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
            <a href="{{ route('admin.categories.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-moon-gold to-yellow-500 text-moon-dark font-bold text-sm rounded-lg hover:shadow-[0_0_15px_rgba(255,215,0,0.3)] transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Category</span>
            </a>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-moon-dark/40 backdrop-blur-md rounded-2xl border border-white/5 overflow-hidden ring-1 ring-white/5 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/5 bg-white/5">
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest pl-8">Name</th>
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Slug</th>
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest text-right pr-8">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-sm">
                    @forelse($categories as $category)
                        <tr class="group hover:bg-white/[0.02] transition-colors duration-200">
                            <td class="px-6 py-4 pl-8">
                                <div class="font-medium text-white text-base group-hover:text-moon-gold transition-colors">{{ $category->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-moon-gray-400 font-mono text-xs">
                                {{ $category->slug }}
                            </td>
                            <td class="px-6 py-4 text-right pr-8">
                                <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="p-2 text-moon-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-all" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-moon-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-32 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <h3 class="text-2xl font-bold text-white mb-2 font-display">No Categories Found</h3>
                                    <p class="text-moon-gray-400 mb-8 max-w-sm mx-auto">Organize your products with categories.</p>
                                    <a href="{{ route('admin.categories.create') }}" 
                                       class="px-8 py-3 bg-moon-gold text-moon-dark font-bold rounded-xl hover:bg-yellow-500 transition-all shadow-lg hover:shadow-moon-gold/20">
                                        Create Category
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-white/5 bg-white/[0.02]">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
