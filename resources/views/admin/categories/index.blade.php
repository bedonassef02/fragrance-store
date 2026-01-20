@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <x-admin.ui.page-header title="Categories" description="Manage product categories.">
        <x-slot name="actions">
            <x-admin.ui.button type="a" href="{{ route('admin.categories.create') }}" icon="plus">
                <x-slot name="icon">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                </x-slot>
                New Category
            </x-admin.ui.button>
        </x-slot>
    </x-admin.ui.page-header>

    <x-admin.ui.glass-panel class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-700/50 text-slate-400 text-xs uppercase tracking-wider">
                        <th class="p-4 font-medium">Name</th>
                        <th class="p-4 font-medium">Slug</th>
                        <th class="p-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($categories as $category)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="p-4 font-medium text-white">{{ $category->name }}</td>
                            <td class="p-4 text-slate-400 font-mono text-xs">{{ $category->slug }}</td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <x-admin.ui.button type="a" href="{{ route('admin.categories.edit', $category) }}" variant="icon" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </x-admin.ui.button>

                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                            <td colspan="3" class="p-12 text-center text-slate-500">
                                <p>No categories found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($categories->hasPages())
            <div class="px-4 py-3 border-t border-slate-700/50">
                {{ $categories->links() }}
            </div>
        @endif
    </x-admin.ui.glass-panel>
@endsection
