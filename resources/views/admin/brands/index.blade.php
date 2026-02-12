@extends('layouts.admin')

@section('title', 'Brands')

@section('header')
<x-admin.layout.header title="Brands" subtitle="Manage your perfume brands and their visibility." />
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filters & Search -->
    <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/5 rounded-2xl p-4 flex flex-col md:flex-row gap-4 items-center justify-between">
         <x-admin.data.search name="search" placeholder="Search brands..." />
        
        <div class="flex items-center gap-3">
            <x-admin.ui.button href="{{ route('admin.brands.create') }}" icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>'>
                Add Brand
            </x-admin.ui.button>
        </div>
    </div>

    <!-- Brands Table -->
    <x-admin.data.table :headers="['Brand', 'Perfumes', 'Type', 'Actions']" :pagination="$brands->hasPages() ? $brands->withQueryString()->links() : null">
        @forelse($brands as $brand)
            <tr class="group hover:bg-white/[0.02] transition-colors duration-200">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 overflow-hidden shrink-0 flex items-center justify-center">
                            @if($brand->image)
                                <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xl font-bold text-moon-gray-500">{{ substr($brand->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <div class="text-white font-bold group-hover:text-moon-gold transition-colors">{{ $brand->name }}</div>
                            <div class="text-xs text-moon-gray-500 font-mono mt-0.5">{{ $brand->slug }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-xs font-medium text-moon-gray-300">
                        <svg class="w-3.5 h-3.5 text-moon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span>{{ $brand->products_count }} Products</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($brand->is_luxury)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-moon-gold/10 text-moon-gold border border-moon-gold/20">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Luxury
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-white/5 text-moon-gray-400 border border-white/10">Standard</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                        <x-admin.ui.button href="{{ route('admin.brands.edit', $brand) }}" variant="ghost" class="p-2" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>' />
                        
                        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure? This will remove the brand from all associated products.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="p-2 text-moon-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all"
                                    title="Delete Brand">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
             <x-admin.data.empty-state 
                title="No Brands Found" 
                message="Start by adding brands to organize your perfumes." 
                action="{{ route('admin.brands.create') }}" 
                actionLabel="Add First Brand" 
            />
        @endforelse
    </x-admin.data.table>
</div>
@endsection
