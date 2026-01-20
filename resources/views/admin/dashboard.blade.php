@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
    <x-admin.ui.page-header title="Overview" description="Here is what's happening with your store today." />

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Revenue -->
        <x-admin.ui.glass-panel class="p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-16 h-16 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/></svg>
            </div>
            <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Total Revenue</p>
            <h3 class="text-3xl font-bold text-white">{{ number_format($stats['total_revenue']) }} LE</h3>
            <div class="mt-4 flex items-center text-xs font-medium text-emerald-400">
                <span class="bg-emerald-400/10 px-2 py-1 rounded-full">+12.5% vs last month</span>
            </div>
        </x-admin.ui.glass-panel>

        <!-- Orders -->
        <x-admin.ui.glass-panel class="p-6 relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-16 h-16 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" /><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Total Orders</p>
            <h3 class="text-3xl font-bold text-white">{{ number_format($stats['total_orders']) }}</h3>
            <div class="mt-4 flex items-center text-xs font-medium text-blue-400">
                <span class="bg-blue-400/10 px-2 py-1 rounded-full">{{ $stats['pending_orders'] }} Pending Processing</span>
            </div>
        </x-admin.ui.glass-panel>

        <!-- Products -->
        <x-admin.ui.glass-panel class="p-6 relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-16 h-16 text-purple-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Active Products</p>
            <h3 class="text-3xl font-bold text-white">{{ number_format($stats['total_products']) }}</h3>
            <div class="mt-4 flex items-center text-xs font-medium text-purple-400">
                @if($stats['low_stock_products'] > 0)
                <span class="bg-red-400/10 text-red-400 px-2 py-1 rounded-full">{{ $stats['low_stock_products'] }} Low Stock Alert</span>
                @else
                <span class="bg-purple-400/10 px-2 py-1 rounded-full">Inventory Healthy</span>
                @endif
            </div>
        </x-admin.ui.glass-panel>

        <!-- Avg Order Value (Replaced Customers) -->
         <x-admin.ui.glass-panel class="p-6 relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
               <svg class="w-16 h-16 text-pink-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/></svg>
            </div>
            <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Avg. Order Value</p>
            <h3 class="text-3xl font-bold text-white">{{ number_format($stats['average_order_value']) }} LE</h3>
            <div class="mt-4 flex items-center text-xs font-medium text-pink-400">
                <span class="bg-pink-400/10 px-2 py-1 rounded-full">per order</span>
            </div>
        </x-admin.ui.glass-panel>
    </div>

    <!-- Recent Orders Table -->
    <x-admin.ui.glass-panel class="overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-700/50 flex justify-between items-center bg-white/5">
            <h3 class="font-bold text-white text-lg">Recent Orders</h3>
            <a href="#" class="text-sm font-medium text-blue-400 hover:text-blue-300 transition-colors">View All Orders &rarr;</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-400">
                <thead class="bg-slate-900/50 uppercase tracking-wider text-xs font-bold text-slate-500">
                    <tr>
                        <th class="px-8 py-4">Order ID</th>
                        <th class="px-8 py-4">Customer</th>
                        <th class="px-8 py-4">Date</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Total</th>
                        <th class="px-8 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-8 py-4 font-mono text-blue-400 font-medium">#{{ $order->order_number }}</td>
                        <td class="px-8 py-4 text-white font-medium flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-300">
                                {{ substr($order->first_name, 0, 1) }}
                            </div>
                            {{ $order->first_name }} {{ $order->last_name }}
                        </td>
                        <td class="px-8 py-4">{{ $order->created_at->format('M d, Y') }}<br><span class="text-xs opacity-60">{{ $order->created_at->format('h:i A') }}</span></td>
                        <td class="px-8 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'amber',
                                    'processing' => 'blue',
                                    'shipped' => 'blue',
                                    'delivered' => 'emerald',
                                    'completed' => 'emerald',
                                    'cancelled' => 'rose',
                                ];
                            @endphp
                            <x-admin.ui.badge :color="$statusColors[$order->status] ?? 'slate'" :label="$order->status" />
                        </td>
                        <td class="px-8 py-4 text-right font-bold text-white">{{ number_format($order->total_amount) }} LE</td>
                        <td class="px-8 py-4 text-right">
                            <x-admin.ui.button type="a" href="#" variant="icon">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" /></svg>
                            </x-admin.ui.button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-12 text-center text-slate-500 italic">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.ui.glass-panel>
@endsection
