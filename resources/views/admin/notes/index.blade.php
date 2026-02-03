@extends('layouts.admin')

@section('title', 'Manage Notes')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-white tracking-tight font-display">Olfactory Notes</h1>
        <p class="text-moon-gray-400 mt-1">Manage fragrance ingredients and notes.</p>
    </div>
    <!-- Filter Toolbar -->
    <div class="flex items-center gap-3">
        <div class="relative">
            <input type="text" placeholder="Search notes..." 
                   class="bg-white/5 border border-white/10 rounded-lg pl-10 pr-4 py-2 text-sm text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all w-64">
            <svg class="w-4 h-4 text-moon-gray-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        
        <div class="h-6 w-px bg-white/10 mx-2"></div>

        <a href="{{ route('admin.notes.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-moon-gold to-yellow-500 text-moon-dark font-bold text-sm rounded-lg hover:shadow-[0_0_15px_rgba(255,215,0,0.3)] transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Add Note</span>
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="bg-moon-dark/50 backdrop-blur-md border border-white/5 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/5">
                    <th class="px-6 py-4 text-xs font-bold text-moon-gray-400 uppercase tracking-widest pl-8">Note Name</th>
                    <th class="px-6 py-4 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Type</th>
                    <th class="px-6 py-4 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Usage</th>
                    <th class="px-6 py-4 text-xs font-bold text-moon-gray-400 uppercase tracking-widest text-right pr-8">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-sm">
                @forelse($notes as $note)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-4 pl-8">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center overflow-hidden border border-white/5 shrink-0 group-hover:border-moon-gold/30 transition-colors">
                                    @if($note->image)
                                        <img src="{{ Storage::url($note->image) }}" alt="{{ $note->name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-moon-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    @endif
                                </div>
                                <div class="font-medium text-white text-base group-hover:text-moon-gold transition-colors">{{ $note->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'top' => 'text-yellow-400 bg-yellow-400/10 border-yellow-400/20',
                                    'heart' => 'text-pink-400 bg-pink-400/10 border-pink-400/20',
                                    'base' => 'text-purple-400 bg-purple-400/10 border-purple-400/20',
                                    'general' => 'text-moon-gray-300 bg-white/5 border-white/10'
                                ];
                                $colorClass = $colors[$note->type] ?? $colors['general'];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full border {{ $colorClass }} text-xs font-bold uppercase tracking-wider">
                                {{ ucfirst($note->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-moon-gray-400">{{ $note->products_count }} Products</span>
                        </td>
                        <td class="px-6 py-4 text-right pr-8">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.notes.edit', $note) }}" 
                                   class="p-2 text-moon-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-all" title="Edit Note">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Delete this note?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-moon-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all" title="Delete Note">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-24 text-center">
                            <div class="w-16 h-16 mx-auto bg-white/5 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-moon-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white">No Notes Found</h3>
                            <p class="text-moon-gray-400 mt-1 mb-6">Start building your fragrance library.</p>
                            <a href="{{ route('admin.notes.create') }}" 
                               class="inline-block px-6 py-2 bg-moon-gold text-moon-dark font-bold rounded-lg hover:bg-yellow-400 transition-colors">
                                Add Note
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($notes->hasPages())
        <div class="px-6 py-4 border-t border-white/5">
            {{ $notes->links() }}
        </div>
    @endif
</div>
@endsection
