@extends('layouts.admin')

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.orders.index') }}" class="p-2 rounded-xl bg-slate-800/50 hover:bg-slate-700 text-slate-400 hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>
    Order <span class="text-moon-gold font-mono ml-2">#{{ $order->order_number }}</span>
</div>
@endsection

@section('subheader')
Placed on <span class="text-white font-medium">{{ $order->created_at->format('F d, Y') }}</span> at {{ $order->created_at->format('h:i A') }}
@endsection

@section('actions')
<form action="{{ route('admin.orders.update', $order) }}" method="POST" class="flex items-center">
    @csrf
    @method('PUT')
    <div class="relative group">
        <select name="status" onchange="this.form.submit()" class="appearance-none bg-[#1e293b] border border-[#334155] text-white pl-4 pr-10 py-2.5 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-moon-gold/50 hover:border-moon-gold/50 transition-colors cursor-pointer capitalize">
            @foreach(['pending', 'processing', 'shipped', 'delivered', 'returned', 'replaced', 'cancelled'] as $status)
                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400 group-hover:text-moon-gold transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
        </div>
    </div>
</form>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Items -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-[#1e293b]/50 backdrop-blur-md rounded-2xl border border-[#334155] overflow-hidden">
            <div class="px-6 py-4 border-b border-[#334155] flex items-center justify-between">
                <h3 class="font-serif text-lg text-white font-medium">Order Items</h3>
                <span class="text-xs text-slate-400 font-medium uppercase tracking-wider">{{ $order->items_count }} Items</span>
            </div>
            
            <div class="divide-y divide-[#334155]">
                @foreach($order->items as $item)
                    <div class="p-6 flex items-start gap-6 group hover:bg-white/5 transition-colors">
                        <!-- Product Image -->
                        <div class="w-20 h-24 rounded-lg bg-slate-800 overflow-hidden flex-shrink-0 border border-[#334155]">
                            @if($item->product && $item->product->images && count($item->product->images) > 0)
                                <img src="{{ $item->product->images[0]->image_path }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-600">
                                    <svg class="w-8 h-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-white font-medium text-lg leading-tight">{{ $item->product_name }}</h4>
                                    @if($item->variant_key)
                                        <div class="mt-1 inline-flex items-center px-2 py-0.5 rounded textxs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                                            {{ $item->size }} / {{ $item->color }}
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="text-white font-serif font-bold">${{ number_format($item->total, 2) }}</div>
                                    <div class="text-xs text-slate-500 mt-1">${{ number_format($item->unit_price, 2) }} x {{ $item->quantity }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Totals -->
            <div class="bg-[#0f172a]/30 px-6 py-4 border-t border-[#334155]">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Subtotal</span>
                        <span class="text-white">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Discount <span class="text-xs bg-slate-800 text-slate-300 px-1.5 py-0.5 rounded ml-2">{{ $order->coupon_code }}</span></span>
                            <span class="text-emerald-400">-${{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-lg pt-4 border-t border-[#334155]">
                        <span class="font-serif font-bold text-white">Total</span>
                        <span class="font-serif font-bold text-moon-gold">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Sidebar -->
    <div class="space-y-6">
        <!-- Customer Card -->
        <div class="bg-[#1e293b]/50 backdrop-blur-md rounded-2xl border border-[#334155] p-6">
            <h3 class="font-serif text-lg text-white font-medium mb-4">Customer</h3>
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full bg-slate-700 flex items-center justify-center text-lg font-serif text-white border border-slate-600">
                    {{ strtoupper(substr($order->first_name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-white font-bold truncate">{{ $order->first_name }} {{ $order->last_name }}</div>
                    <div class="text-sm text-slate-400 truncate">{{ $order->email }}</div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-slate-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span class="text-slate-300 text-sm">{{ $order->phone ?? 'No phone' }}</span>
                </div>
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-slate-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="text-slate-300 text-sm leading-relaxed">
                        {{ $order->address }}<br>
                        {{ $order->city }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Payment Card -->
        <div class="bg-[#1e293b]/50 backdrop-blur-md rounded-2xl border border-[#334155] p-6">
            <h3 class="font-serif text-lg text-white font-medium mb-4">Payment</h3>
            <div class="flex items-center justify-between mb-2">
                <span class="text-slate-400 text-sm">Method</span>
                <span class="text-white font-medium capitalize flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ str_replace('_', ' ', $order->payment_method) }}
                </span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-sm">Payment Status</span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 text-xs font-bold uppercase tracking-wider">
                    Paid
                </span>
            </div>
        </div>

        <!-- Deposit Card -->
        <div class="bg-[#1e293b]/50 backdrop-blur-md rounded-2xl border border-[#334155] p-6" x-data="{ editing: false }">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-serif text-lg text-white font-medium">Deposit</h3>
                @if($order->deposit_amount)
                    <div class="flex items-center gap-2">
                         <button @click="editing = !editing" class="text-xs font-bold uppercase tracking-wider text-moon-gold hover:text-white transition-colors">
                            <span x-text="editing ? 'Cancel' : 'Edit'">Edit</span>
                        </button>
                        <form action="{{ route('admin.orders.deposit.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this deposit?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold uppercase tracking-wider text-red-500 hover:text-red-400 transition-colors">
                                Remove
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            
            @if($order->deposit_amount)
                <div x-show="!editing" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-sm">Paid Amount</span>
                        <span class="text-moon-gold font-bold font-mono text-lg">{{ number_format($order->deposit_amount, 2) }} LE</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-sm">Remaining</span>
                        <span class="text-white font-bold font-mono">{{ number_format($order->total_amount - $order->deposit_amount, 2) }} LE</span>
                    </div>
                    @if($order->deposit_proof_path)
                        <div class="pt-4 border-t border-[#334155]">
                            <a href="{{ Storage::url($order->deposit_proof_path) }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2 rounded-lg bg-blue-500/10 text-blue-400 border border-blue-500/20 hover:bg-blue-500/20 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                View Proof Screenshot
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Update Form -->
                <form x-show="editing" action="{{ route('admin.orders.deposit', $order) }}" method="POST" enctype="multipart/form-data" class="space-y-4 mt-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Update Amount</label>
                        <div class="relative">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 font-serif">LE</span>
                            </div>
                            <input type="number" step="0.01" name="deposit_amount" value="{{ $order->deposit_amount }}" class="w-full bg-[#0f172a] border border-[#334155] rounded-xl pl-10 pr-4 py-2 text-white text-sm focus:border-moon-gold focus:ring-1 focus:ring-moon-gold placeholder-slate-600 transition-colors">
                        </div>
                        @error('deposit_amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Update Screenshot</label>
                        <input type="file" name="deposit_proof" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-white hover:file:bg-slate-600">
                        @error('deposit_proof')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                         <p class="text-[10px] text-slate-500 mt-1">Leave empty to keep current proof.</p>
                    </div>

                    <button type="submit" class="w-full py-2 bg-moon-gold hover:bg-yellow-500 text-moon-dark font-bold rounded-xl text-sm transition-colors shadow-lg shadow-moon-gold/20">
                        Update Deposit
                    </button>
                </form>

            @else
                <form action="{{ route('admin.orders.deposit', $order) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Deposit Amount</label>
                        <div class="relative">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 font-serif">LE</span>
                            </div>
                            <input type="number" step="0.01" name="deposit_amount" placeholder="0.00" class="w-full bg-[#0f172a] border border-[#334155] rounded-xl pl-10 pr-4 py-2 text-white text-sm focus:border-moon-gold focus:ring-1 focus:ring-moon-gold placeholder-slate-600 transition-colors">
                        </div>
                        @error('deposit_amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Proof Screenshot</label>
                        <input type="file" name="deposit_proof" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-white hover:file:bg-slate-600">
                        @error('deposit_proof')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-2 bg-moon-gold hover:bg-yellow-500 text-moon-dark font-bold rounded-xl text-sm transition-colors shadow-lg shadow-moon-gold/20">
                        Add Deposit
                    </button>
                </form>
            @endif
        </div>

<style>
    .bg-moon-gold { background-color: #d4af37; }
    .text-moon-gold { color: #d4af37; }
    .focus\:ring-moon-gold\/50:focus { --tw-ring-color: rgba(212, 175, 55, 0.5); }
    .hover\:border-moon-gold\/50:hover { border-color: rgba(212, 175, 55, 0.5); }
</style>
@endsection
