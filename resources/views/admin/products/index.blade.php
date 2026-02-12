@extends('layouts.admin')

@section('title', 'Products')

@section('header')
<x-admin.layout.header title="Products" subtitle="Manage your perfume catalog and inventory." />
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filters & Search -->
    <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/5 rounded-2xl p-4 flex flex-col md:flex-row gap-4 items-center justify-between">
        <x-admin.data.search name="search" placeholder="Search by name, brand..." />
        
        <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
            <x-admin.data.filter href="{{ route('admin.products.index', ['search' => request('search')]) }}" :active="!request('status')">
                All Perfumes
            </x-admin.data.filter>
            
            <x-admin.data.filter href="{{ route('admin.products.index', ['status' => 'out_of_stock', 'search' => request('search')]) }}" :active="request('status') === 'out_of_stock'">
                Out of Stock
            </x-admin.data.filter>
            
            <x-admin.data.filter href="{{ route('admin.products.index', ['status' => 'featured', 'search' => request('search')]) }}" :active="request('status') === 'featured'">
                Featured
            </x-admin.data.filter>
            
            <div class="h-6 w-px bg-white/10 mx-2"></div>

            <x-admin.ui.button href="{{ route('admin.products.create') }}" icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>'>
                Add Perfume
            </x-admin.ui.button>
        </div>
    </div>

    <!-- Products Table -->
    <x-admin.data.table :headers="['Perfume', 'Brand', 'Profile', 'Price', 'Inventory', 'Actions']" :pagination="$products->hasPages() ? $products->withQueryString()->links() : null">
        @forelse($products as $product)
            <tr class="group hover:bg-white/[0.02] transition-colors duration-200">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-moon-dark border border-white/10 overflow-hidden relative group-hover:border-moon-gold/30 transition-colors shrink-0">
                            @if($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-moon-gray-600 bg-white/5">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <!-- Badge overlay -->
                            @if($product->badge)
                                <div class="absolute top-0 right-0 bg-{{ $product->badge_color ?? 'moon-gold' }} text-[10px] font-bold px-1.5 py-0.5 rounded-bl-lg text-moon-dark">
                                    {{ $product->badge }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="text-white font-bold group-hover:text-moon-gold transition-colors text-lg font-display">{{ $product->name }}</div>
                            <div class="text-xs text-moon-gray-500 font-mono mt-0.5">
                                {{ $product->category ? $product->category->name : 'Uncategorized' }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($product->brand)
                        <span class="text-moon-gray-300 font-medium">{{ $product->brand->name }}</span>
                    @else
                        <span class="text-moon-gray-600">-</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col gap-1">
                        @if($product->concentration)
                            <span class="inline-flex items-center w-fit px-2 py-0.5 rounded text-[10px] font-bold bg-white/5 text-moon-gray-300 border border-white/10">
                                {{ $product->concentration }}
                            </span>
                        @endif
                        @if($product->gender)
                            <span class="text-xs text-moon-gray-500 capitalize">{{ $product->gender }}</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col">
                        <div class="text-white font-bold font-mono text-base">{{ number_format($product->price, 2) }} LE</div>
                        @if($product->original_price)
                            <div class="text-xs text-moon-gray-500 line-through font-mono">{{ number_format($product->original_price, 2) }} LE</div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    @php
                        $totalStock = $product->variants->sum('quantity');
                        $variantCount = $product->variants->count();
                    @endphp
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $totalStock > 0 ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]' : 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.4)]' }}"></span>
                            <span class="{{ $totalStock > 0 ? 'text-green-400' : 'text-red-400' }} font-bold text-sm">
                                {{ $totalStock }} in stock
                            </span>
                        </div>
                        <div class="text-xs text-moon-gray-500 ml-4">{{ $variantCount }} variant{{ $variantCount !== 1 ? 's' : '' }}</div>
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                        <x-admin.ui.button href="{{ route('admin.products.edit', $product) }}" variant="ghost" class="p-2" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>' />
                        
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="p-2 text-moon-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all"
                                    title="Delete Product">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <x-admin.data.empty-state 
                title="No Perfumes Found" 
                message="Your catalog is empty. Start adding perfumes to your store." 
                action="{{ route('admin.products.create') }}" 
                actionLabel="Add First Perfume" 
            />
        @endforelse
    </x-admin.data.table>
</div>
@endsection
