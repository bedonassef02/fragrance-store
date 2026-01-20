@extends('layouts.admin')

@section('title', 'Collections')

@section('content')
    <x-admin.ui.page-header title="Collections" description="Manage your product collections.">
        <x-slot name="actions">
            <x-admin.ui.button type="a" href="{{ route('admin.collections.create') }}" icon="plus">
                <x-slot name="icon">
                     <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                </x-slot>
                New Collection
            </x-admin.ui.button>
        </x-slot>
    </x-admin.ui.page-header>

    <x-admin.ui.glass-panel class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-700/50 text-slate-400 text-xs uppercase tracking-wider">
                        <th class="p-4 font-medium">Image</th>
                        <th class="p-4 font-medium">Title</th>
                        <th class="p-4 font-medium">Slug</th>
                        <th class="p-4 font-medium">Layout</th>
                        <th class="p-4 font-medium">Sort Order</th>
                        <th class="p-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($collections as $collection)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="p-4">
                                @if($collection->image)
                                    <div class="w-12 h-12 rounded-lg bg-slate-800 bg-cover bg-center border border-slate-700" style="background-image: url('{{ Str::startsWith($collection->image, ['http','https']) ? $collection->image : asset('storage/' . $collection->image) }}')"></div>
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-slate-800 flex items-center justify-center text-slate-600 border border-slate-700">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="p-4 font-medium text-white">{{ $collection->title }}</td>
                            <td class="p-4 text-slate-400 font-mono text-xs">{{ $collection->slug }}</td>
                            <td class="p-4 text-slate-400">{{ $collection->layout_class ?? 'Default' }}</td>
                            <td class="p-4 text-slate-400">{{ $collection->sort_order }}</td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <x-admin.ui.button type="a" href="{{ route('admin.collections.edit', $collection) }}" variant="icon" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </x-admin.ui.button>

                                    <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this collection?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-admin.ui.button type="submit" variant="icon" class="text-rose-400 hover:bg-rose-500/10 hover:text-rose-300" title="Delete">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </x-admin.ui.button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-500">
                                <p>No collections found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($collections->hasPages())
            <div class="px-4 py-3 border-t border-slate-700/50">
                {{ $collections->links() }}
            </div>
        @endif
    </x-admin.ui.glass-panel>
@endsection
