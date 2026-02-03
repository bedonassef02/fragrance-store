@extends('layouts.admin')

@section('title', 'Collections')

@section('header')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-white tracking-tight font-display">Collections</h1>
        <p class="text-moon-gray-400 mt-1">Manage your product collections.</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Toolbar -->
    <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/5 rounded-2xl p-4 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <!-- Search placeholder if needed, or just empty div if no search implemented yet -->
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
            <a href="{{ route('admin.collections.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-moon-gold to-yellow-500 text-moon-dark font-bold text-sm rounded-lg hover:shadow-[0_0_15px_rgba(255,215,0,0.3)] transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Collection</span>
            </a>
        </div>
    </div>

    <!-- Collections Table -->
    <div class="bg-moon-dark/40 backdrop-blur-md rounded-2xl border border-white/5 overflow-hidden ring-1 ring-white/5 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/5 bg-white/5">
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest pl-8">Image</th>
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Title</th>
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Slug</th>
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Layout</th>
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Sort</th>
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest text-right pr-8">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-sm">
                    @forelse($collections as $collection)
                        <tr class="group hover:bg-white/[0.02] transition-colors duration-200">
                            <td class="px-6 py-4 pl-8">
                                <div class="w-12 h-12 rounded-lg bg-white/10 flex items-center justify-center overflow-hidden border border-white/5 shrink-0 group-hover:border-moon-gold/30 transition-colors">
                                    @if($collection->image)
                                        <div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ Str::startsWith($collection->image, ['http','https']) ? $collection->image : asset('storage/' . $collection->image) }}')"></div>
                                    @else
                                        <svg class="w-6 h-6 text-moon-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-white text-base group-hover:text-moon-gold transition-colors">{{ $collection->title }}</div>
                            </td>
                            <td class="px-6 py-4 text-moon-gray-400 font-mono text-xs">
                                {{ $collection->slug }}
                            </td>
                            <td class="px-6 py-4 text-moon-gray-400">
                                {{ $collection->layout_class ?? 'Default' }}
                            </td>
                            <td class="px-6 py-4 text-moon-gray-400">
                                {{ $collection->sort_order }}
                            </td>
                            <td class="px-6 py-4 text-right pr-8">
                                <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.collections.edit', $collection) }}" 
                                       class="p-2 text-moon-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-all" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST" onsubmit="return confirm('Delete this collection?');" class="inline-block">
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
                            <td colspan="6" class="px-6 py-32 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <h3 class="text-2xl font-bold text-white mb-2 font-display">No Collections Found</h3>
                                    <p class="text-moon-gray-400 mb-8 max-w-sm mx-auto">Create collections to group your products.</p>
                                    <a href="{{ route('admin.collections.create') }}" 
                                       class="px-8 py-3 bg-moon-gold text-moon-dark font-bold rounded-xl hover:bg-yellow-500 transition-all shadow-lg hover:shadow-moon-gold/20">
                                        Create Collection
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($collections->hasPages())
            <div class="px-6 py-4 border-t border-white/5 bg-white/[0.02]">
                {{ $collections->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
