@extends('layouts.admin')

@section('header', 'Orders')
@section('subheader', 'Manage and fulfill customer orders.')

@section('content')
<div class="space-y-6">
    <!-- Stats / Filters Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Status Filter -->
        <div class="md:col-span-3 bg-[#1e293b]/50 backdrop-blur-md rounded-2xl border border-[#334155] p-1.5 flex flex-wrap gap-1">
            <a href="{{ route('admin.orders.index') }}" 
               class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 {{ !request('status') ? 'bg-moon-gold text-white shadow-lg shadow-moon-gold/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                All
            </a>
            @foreach(['pending', 'processing', 'shipped', 'delivered', 'returned', 'replaced', 'cancelled'] as $status)
                <a href="{{ route('admin.orders.index', ['status' => $status]) }}" 
                   class="px-4 py-2 rounded-xl text-sm font-medium capitalize transition-all duration-300 {{ request('status') === $status ? 'bg-moon-gold text-white shadow-lg shadow-moon-gold/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    {{ $status }}
                </a>
            @endforeach
        </div>
        
        <!-- Search (Placeholder for now) -->
        <div class="bg-[#1e293b]/50 backdrop-blur-md rounded-2xl border border-[#334155] p-1.5 relative">
            <input type="text" placeholder="Search orders..." class="w-full h-full bg-transparent border-none text-sm text-white placeholder-slate-500 focus:ring-0 pl-10">
            <svg class="w-4 h-4 text-slate-500 absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <!-- Orders List -->
    <div class="bg-[#1e293b]/50 backdrop-blur-md rounded-2xl border border-[#334155] overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-slate-400 border-b border-[#334155] text-xs uppercase tracking-wider">
                    <th class="p-6 font-medium">Order</th>
                    <th class="p-6 font-medium">Customer</th>
                    <th class="p-6 font-medium">Items</th>
                    <th class="p-6 font-medium">Total</th>
                    <th class="p-6 font-medium">Status</th>
                    <th class="p-6 font-medium text-right">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#334155]">
                @forelse($orders as $order)
                    <tr class="group hover:bg-white/5 transition-colors duration-200 cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $order) }}'">
                        <td class="p-6">
                            <span class="font-mono text-moon-gold font-medium">#{{ $order->order_number }}</span>
                        </td>
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-white uppercase border border-slate-600">
                                    {{ substr($order->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-white font-medium text-sm">{{ $order->first_name }} {{ $order->last_name }}</div>
                                    <div class="text-xs text-slate-500">{{ $order->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300 text-xs font-medium border border-slate-700">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                {{ $order->items_count }}
                            </span>
                        </td>
                        <td class="p-6">
                            <span class="text-white font-serif font-bold tracking-wide">${{ number_format($order->total_amount, 2) }}</span>
                        </td>
                        <td class="p-6">
                             @php
                                $statusStyles = [
                                    'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                    'processing' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                    'shipped' => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
                                    'delivered' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                    'returned' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
                                    'replaced' => 'bg-cyan-500/10 text-cyan-500 border-cyan-500/20',
                                    'cancelled' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $statusStyles[$order->status] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20' }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="p-6 text-right">
                             <div class="text-slate-300 text-sm font-medium">{{ $order->created_at->format('M d') }}</div>
                             <div class="text-xs text-slate-500">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-500">
                             <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-800/50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h3 class="text-white font-medium text-lg">No orders found</h3>
                                <p class="text-sm mt-1">There are no orders matching your criteria.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($orders->hasPages())
            <div class="p-6 border-t border-[#334155]">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
<style>
    /* Custom Scrollbar for this page if needed */
    .bg-moon-gold { background-color: #d4af37; }
    .text-moon-gold { color: #d4af37; }
    .shadow-moon-gold\/20 { --tw-shadow-color: rgba(212, 175, 55, 0.2); --tw-shadow: var(--tw-shadow-colored); }
</style>
@endsection
