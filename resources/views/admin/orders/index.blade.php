@extends('layouts.admin')

@section('title', 'Orders')

@section('header')
<x-admin.layout.header title="Orders" subtitle="Track and manage customer orders." />
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filters & Search -->
    <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/5 rounded-2xl p-4 flex flex-col md:flex-row gap-4 items-center justify-between">
        <x-admin.data.search name="search" placeholder="Search order #, email..." />
        
        <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
             <x-admin.data.filter href="{{ route('admin.orders.index', ['search' => request('search')]) }}" :active="!request('status')">
                All Orders
            </x-admin.data.filter>
            
            @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                <x-admin.data.filter href="{{ route('admin.orders.index', ['status' => $status, 'search' => request('search')]) }}" :active="request('status') === $status">
                    {{ ucfirst($status) }}
                </x-admin.data.filter>
            @endforeach
        </div>
    </div>

    <!-- Orders Table -->
    <x-admin.data.table :headers="['Order', 'Customer', 'Items', 'Total', 'Status', 'Date']" :pagination="$orders->hasPages() ? $orders->withQueryString()->links() : null">
        @forelse($orders as $order)
            <tr class="group hover:bg-white/[0.02] transition-colors duration-200 cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $order) }}'">
                <td class="px-6 py-4">
                    <div class="font-mono text-white font-bold group-hover:text-moon-gold transition-colors">
                        #{{ $order->order_number }}
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col">
                        <span class="text-white font-medium">{{ $order->billing_name }}</span>
                        <span class="text-xs text-moon-gray-500">{{ $order->billing_email }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-moon-gray-300 font-mono">{{ $order->items_count }} items</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-white font-bold font-mono">{{ number_format($order->grand_total, 2) }} LE</span>
                </td>
                <td class="px-6 py-4">
                    @php
                        $color = match($order->status) {
                            'pending' => 'yellow',
                            'processing' => 'blue',
                            'shipped' => 'purple',
                            'delivered' => 'green',
                            'cancelled', 'returned' => 'red',
                            'created' => 'gray',
                            default => 'gray',
                        };
                    @endphp
                    <x-admin.ui.badge :color="$color" :label="ucfirst($order->status)" />
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="text-moon-gray-400 text-sm">
                        {{ $order->created_at->format('M d, Y') }}
                    </div>
                    <div class="text-moon-gray-600 text-xs">
                        {{ $order->created_at->format('h:i A') }}
                    </div>
                </td>
            </tr>
        @empty
            <x-admin.data.empty-state 
                title="No Orders Found" 
                message="We couldn't find any orders matching your search." 
            />
        @endforelse
    </x-admin.data.table>
</div>
@endsection
