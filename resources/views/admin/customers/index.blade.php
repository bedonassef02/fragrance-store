@extends('layouts.admin')

@section('title', 'Customers')

@section('header')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-white tracking-tight font-display">Customers</h1>
        <p class="text-moon-gray-400 mt-1">View and manage your customer base.</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filters & Search -->
    <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/5 rounded-2xl p-4 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <form action="{{ route('admin.customers.index') }}" method="GET">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-moon-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email, or phone..." 
                       class="w-full bg-black/20 border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                @if($sort)
                    <input type="hidden" name="sort" value="{{ $sort }}">
                @endif
            </form>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
             <div class="flex bg-black/20 rounded-lg p-1 border border-white/10">
                <a href="{{ route('admin.customers.index', ['sort' => 'latest', 'search' => $search]) }}" 
                   class="px-4 py-1.5 rounded-md text-sm font-medium transition-all {{ $sort === 'latest' ? 'bg-moon-gold text-moon-dark shadow-lg' : 'text-moon-gray-400 hover:text-white' }}">
                    Latest
                </a>
                <a href="{{ route('admin.customers.index', ['sort' => 'highest_spent', 'search' => $search]) }}" 
                   class="px-4 py-1.5 rounded-md text-sm font-medium transition-all {{ $sort === 'highest_spent' ? 'bg-moon-gold text-moon-dark shadow-lg' : 'text-moon-gray-400 hover:text-white' }}">
                    Highest Spenders
                </a>
                <a href="{{ route('admin.customers.index', ['sort' => 'most_orders', 'search' => $search]) }}" 
                   class="px-4 py-1.5 rounded-md text-sm font-medium transition-all {{ $sort === 'most_orders' ? 'bg-moon-gold text-moon-dark shadow-lg' : 'text-moon-gray-400 hover:text-white' }}">
                    Frequent Buyers
                </a>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="bg-moon-dark/40 backdrop-blur-md rounded-2xl border border-white/5 overflow-hidden ring-1 ring-white/5 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/5 bg-white/5">
                        <th class="px-6 py-5 text-left text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Customer</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Contact Info</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Orders</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Total Spent</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Last Order</th>
                        <th class="px-6 py-5 text-right text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($customers as $customer)
                        <tr class="group hover:bg-white/[0.02] transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-moon-gold/10 flex items-center justify-center text-moon-gold font-bold border border-moon-gold/20">
                                        {{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-white font-bold group-hover:text-moon-gold transition-colors font-display">
                                            {{ $customer->first_name }} {{ $customer->last_name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2 text-sm text-moon-gray-300">
                                        <svg class="w-4 h-4 text-moon-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        {{ $customer->email }}
                                    </div>
                                    @if($customer->phone)
                                        <div class="flex items-center gap-2 text-sm text-moon-gray-500">
                                            <svg class="w-4 h-4 text-moon-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                            {{ $customer->phone }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                    {{ $customer->total_orders }} Orders
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-moon-gold font-bold">{{ number_format($customer->total_spent, 2) }} LE</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-moon-gray-400">
                                    {{ \Carbon\Carbon::parse($customer->last_order_date)->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.customers.show', urlencode($customer->email)) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-moon-gold text-xs font-bold uppercase tracking-wider transition-colors border border-white/5 hover:border-moon-gold/30">
                                    History
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-32 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mb-6 ring-1 ring-white/10">
                                        <svg class="w-10 h-10 text-moon-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-white mb-2 font-display">No Customers Found</h3>
                                    <p class="text-moon-gray-400 mb-8 max-w-sm mx-auto">It looks like you haven't received any orders yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($customers->hasPages())
            <div class="px-6 py-4 border-t border-white/5 bg-white/[0.02]">
                {{ $customers->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
