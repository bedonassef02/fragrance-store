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
            <div class="mt-4 flex items-center text-xs font-medium {{ $stats['revenue_growth'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                <span class="{{ $stats['revenue_growth'] >= 0 ? 'bg-emerald-400/10' : 'bg-rose-400/10' }} px-2 py-1 rounded-full">
                    {{ $stats['revenue_growth'] >= 0 ? '+' : '' }}{{ number_format($stats['revenue_growth'], 1) }}% vs last month
                </span>
            </div>
        </x-admin.ui.glass-panel>

        <!-- Orders -->
        <a href="{{ route('admin.orders.index') }}" class="block">
            <x-admin.ui.glass-panel class="p-6 relative overflow-hidden group hover:border-blue-500/50 transition-colors">
                 <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-16 h-16 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" /><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" /></svg>
                </div>
                <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Total Orders</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($stats['total_orders']) }}</h3>
                <div class="mt-4 flex items-center text-xs font-medium text-blue-400">
                    <span class="bg-blue-400/10 px-2 py-1 rounded-full">{{ $stats['pending_orders'] }} Pending Processing</span>
                </div>
            </x-admin.ui.glass-panel>
        </a>

        <!-- Products -->
        <a href="{{ route('admin.products.index') }}" class="block">
            <x-admin.ui.glass-panel class="p-6 relative overflow-hidden group hover:border-purple-500/50 transition-colors">
                 <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-16 h-16 text-purple-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" /></svg>
                </div>
                <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Active Products</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($stats['total_products']) }}</h3>
                <div class="mt-4 flex items-center text-xs font-medium text-purple-400">
                    @if($stats['low_stock_count'] > 0)
                    <span class="bg-red-400/10 text-red-400 px-2 py-1 rounded-full">{{ $stats['low_stock_count'] }} Low Stock Alert</span>
                    @else
                    <span class="bg-purple-400/10 px-2 py-1 rounded-full">Inventory Healthy</span>
                    @endif
                </div>
            </x-admin.ui.glass-panel>
        </a>

        <!-- Avg Order Value -->
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

    <!-- Analytics Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Visits Today -->
        <x-admin.ui.glass-panel class="p-6 relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-16 h-16 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </div>
            <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-1">Page Views Today</p>
            <h3 class="text-3xl font-bold text-white">{{ number_format($analytics['visits_today']) }}</h3>
            <div class="mt-4 flex items-center text-xs font-medium text-cyan-400">
                <span class="bg-cyan-400/10 px-2 py-1 rounded-full">{{ $analytics['unique_visitors_today'] }} Unique Visitors</span>
            </div>
        </x-admin.ui.glass-panel>
        
        <!-- Most Viewed Products -->
        <div class="lg:col-span-3">
             <x-admin.ui.glass-panel class="h-full p-6">
                <h3 class="font-bold text-white text-lg mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-moon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Top Viewed Products (All Time)
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-5 gap-4">
                    @foreach($topViewedProducts as $view)
                        <div class="bg-white/5 rounded-xl p-3 flex flex-col items-center text-center group hover:bg-white/10 transition-colors">
                            <div class="w-12 h-12 rounded-lg bg-black/50 mb-3 overflow-hidden border border-white/10">
                                @if($view->product)
                                    <img src="{{ Storage::url($view->product->image) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="text-xs font-medium text-slate-200 truncate w-full mb-1">{{ $view->product->name ?? 'Unknown Product' }}</div>
                            <div class="text-[10px] text-slate-500 mb-2">{{ $view->product ? number_format($view->product->price) . ' LE' : '-' }}</div>
                            <span class="text-xs font-bold text-moon-gold bg-moon-gold/10 px-2 py-1 rounded-full">
                                {{ $view->views }} Views
                            </span>
                        </div>
                    @endforeach
                    @if($topViewedProducts->isEmpty())
                        <div class="col-span-full text-center text-slate-500 py-4 text-sm">No product views recorded yet.</div>
                    @endif
                </div>
             </x-admin.ui.glass-panel>
        </div>
    </div>

    <!-- Charts & Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        <!-- Weekly Sales Chart -->
        <div class="lg:col-span-2">
            <x-admin.ui.glass-panel class="h-full p-6">
                <h3 class="font-bold text-white text-lg mb-6">Weekly Sales</h3>
                <div class="flex items-end justify-between h-48 space-x-2">
                    @php $maxSale = max($chartData) > 0 ? max($chartData) : 1; @endphp
                    @foreach($chartData as $day => $amount)
                        <div class="flex flex-col items-center flex-1 group h-full justify-end">
                            <div class="w-full bg-blue-500 rounded-t-sm relative group-hover:bg-blue-400 transition-colors" 
                                 style="height: {{ max(($amount / $maxSale) * 100, 2) }}%">
                                 <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10 border border-slate-600 font-mono shadow-lg">
                                     {{ number_format($amount) }} LE
                                 </div>
                            </div>
                            <span class="text-xs text-slate-500 mt-2 font-medium">{{ $day }}</span>
                        </div>
                    @endforeach
                </div>
            </x-admin.ui.glass-panel>
        </div>

        <!-- Top Products & Low Stock -->
        <div class="space-y-6">
            <!-- Top Products -->
             <x-admin.ui.glass-panel class="p-6">
                <h3 class="font-bold text-white text-lg mb-4">Top Selling</h3>
                <div class="space-y-4">
                    @foreach($topProducts as $product)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-300 truncate pr-4" title="{{ $product->product_name }}">{{ $product->product_name }}</span>
                            <span class="text-xs font-bold text-moon-gold bg-moon-gold/10 px-2 py-0.5 rounded">{{ $product->total_sold }} sold</span>
                        </div>
                    @endforeach
                </div>
            </x-admin.ui.glass-panel>

            <!-- Low Stock -->
            @if($lowStockItems->count() > 0)
            <x-admin.ui.glass-panel class="p-6 border-red-500/20">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-white text-lg">Low Stock</h3>
                    <a href="{{ route('admin.products.index') }}" class="text-xs text-red-400 hover:text-red-300">View All</a>
                </div>
                <div class="space-y-3">
                    @foreach($lowStockItems as $variant)
                        <div class="flex items-center justify-between p-2 rounded bg-red-500/5">
                            <div class="min-w-0">
                                <div class="text-sm text-slate-200 truncate">{{ $variant->product->name }}</div>
                                <div class="text-[10px] text-slate-500">{{ $variant->size }} / {{ $variant->color->name ?? 'N/A' }}</div>
                            </div>
                            <span class="text-xs font-bold text-red-500">{{ $variant->quantity }} left</span>
                        </div>
                    @endforeach
                </div>
            </x-admin.ui.glass-panel>
            @endif
        </div>
    </div>

    <!-- Recent Orders Table -->
    <x-admin.ui.glass-panel class="overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-700/50 flex justify-between items-center bg-white/5">
            <h3 class="font-bold text-white text-lg">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-blue-400 hover:text-blue-300 transition-colors">View All Orders &rarr;</a>
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
                                    'returned' => 'orange',
                                    'replaced' => 'cyan',
                                ];
                            @endphp
                            <x-admin.ui.badge :type="$statusColors[$order->status] ?? 'slate'" :label="$order->status" />
                        </td>
                        <td class="px-8 py-4 text-right font-bold text-white">{{ number_format($order->total_amount) }} LE</td>
                        <td class="px-8 py-4 text-right">
                            <x-admin.ui.button type="a" href="{{ route('admin.orders.show', $order) }}" variant="icon">
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
