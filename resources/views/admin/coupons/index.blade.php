@extends('layouts.admin')

@section('title', 'Coupons')

@section('header', 'Coupons')

@section('actions')
    <a href="{{ route('admin.coupons.create') }}" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-bold shadow-lg shadow-blue-900/40 transition-all transform hover:scale-105 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Create Coupon
    </a>
@endsection

@section('content')
    @if($coupons->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 bg-slate-800/30 border border-dashed border-slate-700 rounded-3xl">
            <div class="w-16 h-16 bg-slate-700/50 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
            </div>
            <h3 class="text-xl font-medium text-white mb-2">No coupons yet</h3>
            <p class="text-slate-400 text-center max-w-sm mb-6">Create your first coupon code to start offering discounts to your customers.</p>
            <a href="{{ route('admin.coupons.create') }}" class="text-blue-400 hover:text-blue-300 font-medium">Create one now &rarr;</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($coupons as $coupon)
                @php
                    $isExpired = !$coupon->isValid();
                    $gradient = $isExpired 
                        ? 'from-slate-700 to-slate-800' 
                        : ($coupon->type === 'percent' ? 'from-purple-600 to-indigo-600' : 'from-emerald-500 to-teal-600');
                    $glow = $isExpired ? '' : 'shadow-lg shadow-' . ($coupon->type === 'percent' ? 'purple' : 'emerald') . '-900/20';
                @endphp
                
                <div class="group relative bg-[#1e293b]/80 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden {{ $glow }} hover:transform hover:scale-[1.02] transition-all duration-300">
                    <!-- Ticket Header -->
                    <div class="h-32 bg-gradient-to-br {{ $gradient }} relative p-6 flex flex-col justify-between overflow-hidden">
                        <!-- Decorative Circles -->
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-black/10 rounded-full blur-xl"></div>
                        
                        <div class="relative z-10 flex justify-between items-start">
                            <div class="bg-black/20 backdrop-blur-md rounded-lg px-3 py-1 text-xs font-bold text-white/90 border border-white/10 uppercase tracking-wider">
                                {{ $coupon->type === 'percent' ? 'Percentage' : 'Fixed Amount' }}
                            </div>
                            
                            @if($coupon->type === 'percent' && $coupon->max_discount_amount)
                                <div class="bg-white/20 backdrop-blur-md rounded-lg px-2 py-1 text-[10px] font-bold text-white border border-white/10 shadow-sm">
                                    Up to {{ number_format($coupon->max_discount_amount) }} LE
                                </div>
                            @endif
                        </div>

                        <div class="relative z-10">
                            <h2 class="text-4xl font-black text-white tracking-tight drop-shadow-sm">
                                @if($coupon->type === 'percent')
                                    {{ (float)$coupon->value }}<span class="text-2xl opacity-80">%</span> <span class="text-lg opacity-80 font-medium">OFF</span>
                                @else
                                    <span class="text-lg opacity-80 font-medium">save</span> {{ number_format($coupon->value) }}<span class="text-lg opacity-80 font-medium">LE</span>
                                @endif
                            </h2>
                        </div>
                    </div>

                    <!-- Cutout Divider -->
                    <div class="relative h-4 bg-[#1e293b]/80 my-[-2px] flex items-center justify-between px-2">
                        <div class="w-4 h-4 rounded-full bg-[#0f172a]"></div> <!-- Left Hole -->
                        <div class="h-[2px] w-full bg-slate-700/50 border-t border-dashed border-slate-500/30 mx-2"></div> <!-- Dashed Line -->
                        <div class="w-4 h-4 rounded-full bg-[#0f172a]"></div> <!-- Right Hole -->
                    </div>

                    <!-- Ticket Body -->
                    <div class="p-6 pt-4 space-y-4">
                        <!-- Code -->
                        <div class="text-center">
                             <div class="inline-block relative">
                                <div class="absolute inset-0 bg-blue-500/20 blur-xl rounded-full"></div>
                                <div class="relative bg-slate-900 border border-slate-600 rounded-xl py-3 px-8 cursor-pointer group-hover:border-blue-500/50 transition-colors">
                                    <span class="font-mono text-xl font-bold text-white tracking-[0.1em] selection:bg-blue-500">{{ $coupon->code }}</span>
                                </div>
                             </div>
                             <p class="text-[10px] text-slate-500 mt-2 uppercase tracking-widest">Coupon Code</p>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-4 py-4 border-t border-slate-700/50">
                            <div class="text-center">
                                <div class="text-xs text-slate-400 mb-1">Used</div>
                                <div class="font-bold text-white">
                                    {{ $coupon->used_count }} 
                                    <span class="text-slate-600 font-normal">/ {{ $coupon->usage_limit ?: '∞' }}</span>
                                </div>
                            </div>
                            <div class="text-center border-l border-slate-700/50">
                                <div class="text-xs text-slate-400 mb-1">Status</div>
                                @if(!$isExpired)
                                    <span class="text-emerald-400 font-bold text-xs uppercase">Active</span>
                                @else
                                    <span class="text-rose-400 font-bold text-xs uppercase">Expired</span>
                                @endif
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="flex justify-between items-center text-[10px] font-medium text-slate-500 bg-slate-800/50 rounded-lg p-2">
                            <span>
                                @if($coupon->valid_from)
                                    {{ $coupon->valid_from->format('M d') }}
                                @else
                                    Anytime
                                @endif
                            </span>
                            <span class="w-8 h-[1px] bg-slate-600"></span>
                            <span class="{{ $isExpired ? 'text-rose-500' : '' }}">
                                @if($coupon->valid_to)
                                    {{ $coupon->valid_to->format('M d, Y') }}
                                @else
                                    Forever
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Actions Overlay (Visible on Hover) -->
                    <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="p-2 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-lg text-white transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        </a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Archive this coupon?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-rose-500/80 hover:bg-rose-600 backdrop-blur-md rounded-lg text-white transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($coupons->hasPages())
            <div class="mt-8">
                {{ $coupons->links() }}
            </div>
        @endif
    @endif
@endsection
