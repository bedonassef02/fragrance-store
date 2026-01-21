@extends('layouts.admin')

@section('title', 'Customer Profile')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('admin.customers.index') }}" class="text-moon-gray-400 hover:text-white transition-colors text-sm flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to Customers
            </a>
        </div>
        <h1 class="text-3xl font-bold text-white tracking-tight font-display">Customer Profile</h1>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Stats & Info -->
    <div class="space-y-6">
        <!-- Customer Card -->
        <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl">
            <div class="flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-moon-gold to-yellow-600 p-[2px] mb-4 shadow-lg shadow-moon-gold/20">
                    <div class="w-full h-full rounded-full bg-moon-dark flex items-center justify-center">
                        <span class="text-3xl font-bold text-moon-gold">{{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}</span>
                    </div>
                </div>
                <h2 class="text-xl font-bold text-white font-display">{{ $customer->first_name }} {{ $customer->last_name }}</h2>
                <div class="mt-2 flex items-center gap-2 bg-white/5 px-3 py-1 rounded-full border border-white/5">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-xs font-medium text-moon-gray-300">Active Customer</span>
                </div>
            </div>

            <div class="mt-8 space-y-4 pt-6 border-t border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-moon-gray-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <div class="text-xs text-moon-gray-500 uppercase tracking-wider">Email Address</div>
                        <div class="text-white">{{ $customer->email }}</div>
                    </div>
                </div>
                
                @if($customer->phone)
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-moon-gray-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    </div>
                    <div>
                        <div class="text-xs text-moon-gray-500 uppercase tracking-wider">Phone Number</div>
                        <div class="text-white">{{ $customer->phone }}</div>
                    </div>
                </div>
                @endif

                <div class="flex items-center gap-3">
                     <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-moon-gray-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <div class="text-xs text-moon-gray-500 uppercase tracking-wider">First Order</div>
                        <div class="text-white">{{ \Carbon\Carbon::parse($customer->first_order_date)->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lifetime Stats -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-5 shadow-lg">
                <div class="text-moon-gray-400 text-xs uppercase tracking-wider mb-2">Total Spent</div>
                <div class="text-2xl font-bold text-moon-gold font-mono">{{ number_format($customer->total_spent, 2) }}</div>
                <div class="text-[10px] text-moon-gray-500 mt-1">Lifetime Value</div>
            </div>
            <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-5 shadow-lg">
                <div class="text-moon-gray-400 text-xs uppercase tracking-wider mb-2">Total Orders</div>
                <div class="text-2xl font-bold text-white font-mono">{{ $customer->total_orders }}</div>
                <div class="text-[10px] text-moon-gray-500 mt-1">Completed purchases</div>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="lg:col-span-2">
        <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl h-full flex flex-col">
            <h3 class="text-xl font-bold text-white mb-6 font-display flex items-center gap-2">
                <svg class="w-5 h-5 text-moon-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Order History
            </h3>

            <div class="flex-1 overflow-x-auto rounded-xl border border-white/5">
                <table class="w-full text-left">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-4 py-3 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Order ID</th>
                            <th class="px-4 py-3 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Date</th>
                            <th class="px-4 py-3 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Items</th>
                            <th class="px-4 py-3 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Total</th>
                            <th class="px-4 py-3 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-4 py-3 text-xs font-bold text-moon-gray-400 uppercase tracking-widest text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($orders as $order)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-4 py-3 font-mono text-sm text-white">#{{ $order->id }}</td>
                            <td class="px-4 py-3 text-sm text-moon-gray-300">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-moon-gray-300">
                                {{ $order->items->count() }} items
                            </td>
                            <td class="px-4 py-3 font-mono text-sm text-white">{{ number_format($order->total_amount, 2) }} LE</td>
                            <td class="px-4 py-3">
                                @php
                                    $color = match($order->status) {
                                        'delivered' => 'emerald',
                                        'shipped' => 'primary',
                                        'processing' => 'blue',
                                        'returned' => 'orange',
                                        'replaced' => 'cyan',
                                        'cancelled' => 'rose',
                                        'pending' => 'amber',
                                        default => 'slate',
                                    };
                                @endphp
                                <x-admin.ui.badge :color="$color">
                                    {{ ucfirst($order->status) }}
                                </x-admin.ui.badge>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-moon-gold hover:text-yellow-400 text-xs font-bold uppercase tracking-wider">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
