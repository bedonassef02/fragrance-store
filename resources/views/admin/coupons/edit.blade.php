@extends('layouts.admin')

@section('title', 'Edit Coupon')
@section('header', 'Edit Coupon')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{
    code: '{{ $coupon->code }}',
    type: '{{ $coupon->type }}',
    value: {{ $coupon->value }},
    valid_from: '{{ $coupon->valid_from?->format('Y-m-d') }}',
    valid_to: '{{ $coupon->valid_to?->format('Y-m-d') }}',
    max_discount_amount: '{{ $coupon->max_discount_amount }}',
    bgGradient: 'from-purple-600 to-indigo-600',
    
    updateGradient() {
        this.bgGradient = this.type === 'percent' 
            ? 'from-purple-600 to-indigo-600' 
            : 'from-emerald-500 to-teal-600';
    }
}" x-init="updateGradient(); $watch('type', val => updateGradient())">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Form -->
        <div class="lg:col-span-2">
            <x-admin.ui.glass-panel class="p-8">
                <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Code -->
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-300 mb-2">Coupon Code</label>
                            <div class="relative">
                                <input type="text" x-model="code" name="code" value="{{ old('code', $coupon->code) }}" 
                                    class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-4 pl-12 text-white placeholder-slate-600 focus:ring-2 focus:ring-blue-500 uppercase font-mono tracking-wider text-lg" 
                                    required>
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                </div>
                            </div>
                            @error('code') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Discount Type</label>
                            <div class="grid grid-cols-2 gap-3 p-1 bg-slate-900/50 rounded-xl border border-slate-700">
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="percent" x-model="type" class="peer sr-only">
                                    <div class="text-center py-2.5 rounded-lg text-sm font-medium text-slate-400 peer-checked:bg-purple-600 peer-checked:text-white transition-all">
                                        Percentage (%)
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="fixed" x-model="type" class="peer sr-only">
                                    <div class="text-center py-2.5 rounded-lg text-sm font-medium text-slate-400 peer-checked:bg-emerald-600 peer-checked:text-white transition-all">
                                        Fixed (LE)
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Value -->
                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Discount Value</label>
                            <input type="number" step="0.01" x-model="value" name="value" value="{{ old('value', $coupon->value) }}" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3.5 text-white focus:ring-2 focus:ring-blue-500 font-mono" required>
                            @error('value') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-2 border-t border-slate-700/50 my-2"></div>

                        <!-- Dates -->
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Valid From</label>
                            <input type="date" x-model="valid_from" name="valid_from" value="{{ old('valid_from', $coupon->valid_from?->format('Y-m-d')) }}" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Valid To</label>
                            <input type="date" x-model="valid_to" name="valid_to" value="{{ old('valid_to', $coupon->valid_to?->format('Y-m-d')) }}" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Limits -->
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Usage Limit</label>
                            <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:ring-2 focus:ring-blue-500" placeholder="Unlimited">
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Min Order Amount</label>
                            <input type="number" step="0.01" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                        </div>
                    </div>

                    <div class="pt-8 flex items-center justify-between">
                        <a href="{{ route('admin.coupons.index') }}" class="text-slate-400 hover:text-white transition-colors font-medium">Cancel</a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-bold shadow-lg shadow-blue-900/20 transition-all transform hover:scale-[1.02]">
                            Update Coupon
                        </button>
                    </div>
                </form>
            </x-admin.ui.glass-panel>
        </div>

        <!-- Right: Live Preview -->
        <div class="lg:col-span-1 sticky top-6">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Live Preview</h3>
            
            <!-- Ticket Card Preview -->
            <div class="relative bg-[#1e293b] border border-slate-700 rounded-2xl overflow-hidden shadow-2xl transform transition-all duration-500">
                <!-- Header -->
                <div class="h-40 relative p-6 flex flex-col justify-between overflow-hidden bg-gradient-to-br transition-colors duration-500" :class="bgGradient">
                     <!-- Decorative Circles -->
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-black/10 rounded-full blur-xl"></div>
                    
                    <div class="relative z-10">
                        <div class="bg-black/20 backdrop-blur-md rounded-lg px-3 py-1 text-[10px] font-bold text-white/90 border border-white/10 uppercase tracking-wider inline-block">
                            <span x-text="type === 'percent' ? 'Percentage' : 'Fixed Amount'"></span>
                        </div>
                    </div>

                    <div class="relative z-10 text-center">
                        <h2 class="text-5xl font-black text-white tracking-tight drop-shadow-sm flex items-baseline justify-center gap-1">
                            <template x-if="type === 'fixed'">
                                <span class="text-xl opacity-80 font-medium">save</span>
                            </template>
                            <span x-text="value || 0"></span>
                            <template x-if="type === 'percent'">
                                <span class="text-3xl opacity-80">%</span>
                            </template>
                            <template x-if="type === 'fixed'">
                                <span class="text-2xl opacity-80 font-medium">LE</span>
                            </template>
                        </h2>
                        <template x-if="type === 'percent'">
                            <div class="text-white/80 font-medium text-sm">OFF YOUR ORDER</div>
                        </template>
                        
                        <!-- Cap Badge -->
                        <template x-if="type === 'percent' && max_discount_amount > 0">
                             <div class="inline-block mt-2 bg-white/20 backdrop-blur-md rounded-md px-2 py-0.5 text-[10px] font-bold text-white border border-white/10 shadow-sm">
                                Up to <span x-text="max_discount_amount"></span> LE
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Divider -->
                <div class="relative h-4 bg-[#1e293b] my-[-2px] flex items-center justify-between px-2">
                    <div class="w-4 h-4 rounded-full bg-[#0f172a]"></div>
                    <div class="h-[2px] w-full bg-slate-700 border-t border-dashed border-slate-600 mx-2"></div>
                    <div class="w-4 h-4 rounded-full bg-[#0f172a]"></div>
                </div>

                <!-- Body -->
                <div class="p-6 pt-4 space-y-6 bg-[#1e293b]">
                    <div class="text-center">
                         <div class="inline-block relative w-full">
                            <div class="relative bg-slate-900 border-2 border-dashed border-slate-700 rounded-xl py-4 px-4">
                                <span class="font-mono text-xl font-bold text-white tracking-[0.1em] break-all" x-text="code || 'CODE'"></span>
                            </div>
                         </div>
                         <p class="text-[10px] text-slate-500 mt-2 uppercase tracking-widest">Coupon Code</p>
                    </div>

                    <!-- Validity Preview -->
                    <div class="flex justify-between items-center text-xs font-medium text-slate-500 bg-slate-800 rounded-lg p-3">
                        <div class="text-center flex-1">
                            <div class="text-[10px] uppercase mb-0.5 opacity-60">Starts</div>
                            <span x-text="valid_from || 'Now'" class="text-slate-300"></span>
                        </div>
                        <div class="w-[1px] h-6 bg-slate-700 mx-2"></div>
                        <div class="text-center flex-1">
                             <div class="text-[10px] uppercase mb-0.5 opacity-60">Ends</div>
                            <span x-text="valid_to || 'Never'" class="text-slate-300"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <p class="text-center text-slate-500 text-xs mt-4">
                Live preview of changes
            </p>
        </div>
    </div>
</div>
@endsection
