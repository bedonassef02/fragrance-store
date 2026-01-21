@extends('layouts.admin')

@section('title', 'Products')

@section('header')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-white tracking-tight font-display">Products</h1>
        <p class="text-moon-gray-400 mt-1">Manage your store's inventory and catalog.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-moon-gold to-yellow-500 text-moon-dark font-bold text-sm rounded-lg hover:shadow-[0_0_15px_rgba(255,215,0,0.3)] transition-all transform hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Add Product</span>
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filters & Search -->
    <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/5 rounded-2xl p-4 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <form action="{{ route('admin.products.index') }}" method="GET">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-moon-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products by name, ID..." 
                       class="w-full bg-black/20 border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
            </form>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
            <a href="{{ route('admin.products.index', ['search' => request('search')]) }}" 
               class="px-4 py-2 rounded-lg border text-sm font-medium whitespace-nowrap transition-colors {{ !request('status') ? 'bg-moon-gold/10 text-moon-gold border-moon-gold/20' : 'bg-white/5 text-moon-gray-400 border-white/5 hover:bg-white/10 hover:text-white' }}">
                All Products
            </a>
            <a href="{{ route('admin.products.index', ['status' => 'out_of_stock', 'search' => request('search')]) }}" 
               class="px-4 py-2 rounded-lg border text-sm font-medium whitespace-nowrap transition-colors {{ request('status') === 'out_of_stock' ? 'bg-moon-gold/10 text-moon-gold border-moon-gold/20' : 'bg-white/5 text-moon-gray-400 border-white/5 hover:bg-white/10 hover:text-white' }}">
                Out of Stock
            </a>
            <a href="{{ route('admin.products.index', ['status' => 'featured', 'search' => request('search')]) }}" 
               class="px-4 py-2 rounded-lg border text-sm font-medium whitespace-nowrap transition-colors {{ request('status') === 'featured' ? 'bg-moon-gold/10 text-moon-gold border-moon-gold/20' : 'bg-white/5 text-moon-gray-400 border-white/5 hover:bg-white/10 hover:text-white' }}">
                Featured
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-moon-dark/40 backdrop-blur-md rounded-2xl border border-white/5 overflow-hidden ring-1 ring-white/5 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/5 bg-white/5">
                        <th class="px-6 py-5 text-left text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Product</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Category</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Price</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Inventory</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-5 text-right text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($products as $product)
                        <tr class="group hover:bg-white/[0.02] transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-xl bg-moon-dark border border-white/10 overflow-hidden relative group-hover:border-moon-gold/30 transition-colors shrink-0">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
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
                                        <div class="text-xs text-moon-gray-500 font-mono mt-0.5">ID: #{{ $product->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($product->category)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-white/5 text-moon-gray-300 border border-white/10 group-hover:border-white/20 transition-colors">
                                        {{ $product->category->name }}
                                    </span>
                                @else
                                    <span class="text-moon-gray-600">-</span>
                                @endif
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
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @if($product->featured)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-moon-gold/10 text-moon-gold border border-moon-gold/20 uppercase tracking-wider">Featured</span>
                                    @endif
                                    @if($product->trending)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20 uppercase tracking-wider">Trending</span>
                                    @endif
                                    @if(!$product->featured && !$product->trending)
                                        <span class="text-moon-gray-600 text-xs">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="p-2 text-moon-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-all"
                                       title="Edit Product">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
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
                        <tr>
                            <td colspan="6" class="px-6 py-32 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mb-6 ring-1 ring-white/10">
                                        <svg class="w-10 h-10 text-moon-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-white mb-2 font-display">No Products Found</h3>
                                    <p class="text-moon-gray-400 mb-8 max-w-sm mx-auto">Your inventory is empty. Start adding products to fill up your store.</p>
                                    <a href="{{ route('admin.products.create') }}" 
                                       class="px-8 py-3 bg-moon-gold text-moon-dark font-bold rounded-xl hover:bg-yellow-500 transition-all shadow-lg hover:shadow-moon-gold/20">
                                        Add First Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-white/5 bg-white/[0.02]">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
