@extends('layouts.admin')

@section('title', 'Payment Settings')

@section('content')
    <x-admin.ui.page-header title="Payment Settings" description="Configure payment gateways and methods for your store." />

    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payment Methods -->
        <x-admin.ui.glass-panel class="p-6">
            <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-moon-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Payment Methods
            </h3>

            <div class="space-y-4">
                <!-- COD -->
                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg border border-slate-700">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-white">Cash on Delivery</h4>
                            <p class="text-xs text-slate-500">Pay when order is delivered</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs {{ config('paymob.methods.cod') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-500 bg-slate-500/10' }} px-2 py-1 rounded">
                            {{ config('paymob.methods.cod') ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>

                <!-- Card -->
                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg border border-slate-700">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-white">Credit / Debit Card</h4>
                            <p class="text-xs text-slate-500">Visa, Mastercard, Meeza via Paymob</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if(config('paymob.secret_key'))
                            <span class="text-xs text-emerald-400">Configured</span>
                        @else
                            <span class="text-xs text-amber-400">Not Configured</span>
                        @endif
                        <span class="text-xs {{ config('paymob.methods.card') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-500 bg-slate-500/10' }} px-2 py-1 rounded">
                            {{ config('paymob.methods.card') ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>

                <!-- Wallet -->
                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg border border-slate-700">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-white">Mobile Wallet</h4>
                            <p class="text-xs text-slate-500">Vodafone Cash, Orange Money</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if(config('paymob.integrations.wallet'))
                            <span class="text-xs text-emerald-400">Configured</span>
                        @else
                            <span class="text-xs text-amber-400">Not Configured</span>
                        @endif
                        <span class="text-xs {{ config('paymob.methods.wallet') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-500 bg-slate-500/10' }} px-2 py-1 rounded">
                            {{ config('paymob.methods.wallet') ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>

                <!-- Fawry -->
                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg border border-slate-700">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center">
                            <span class="text-orange-400 font-bold text-xs">F</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-white">Fawry Reference Code</h4>
                            <p class="text-xs text-slate-500">Pay at any Fawry outlet</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-emerald-400">Reference Mode</span>
                        <span class="text-xs {{ config('paymob.methods.fawry') ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-500 bg-slate-500/10' }} px-2 py-1 rounded">
                            {{ config('paymob.methods.fawry') ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                <p class="text-sm text-blue-300">
                    <strong>Note:</strong> To enable/disable payment methods, update your <code class="bg-black/30 px-1 rounded">.env</code> file:
                </p>
                <pre class="mt-2 text-xs text-slate-400 bg-black/30 p-3 rounded overflow-x-auto">PAYMENT_COD_ENABLED=true
PAYMENT_CARD_ENABLED=true
PAYMENT_WALLET_ENABLED=true
PAYMENT_FAWRY_ENABLED=true</pre>
            </div>
        </x-admin.ui.glass-panel>

        <!-- Paymob Configuration -->
        <x-admin.ui.glass-panel class="p-6">
            <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-moon-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Paymob Configuration
            </h3>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">API Key</p>
                        <p class="text-sm text-white font-mono truncate">
                            {{ config('paymob.api_key') ? '••••••' . substr(config('paymob.api_key'), -4) : 'Not set' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Secret Key</p>
                        <p class="text-sm text-white font-mono truncate">
                            {{ config('paymob.secret_key') ? '••••••' . substr(config('paymob.secret_key'), -4) : 'Not set' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Card Integration ID</p>
                        <p class="text-sm text-white font-mono">
                            {{ config('paymob.integrations.card') ?: 'Not set' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Wallet Integration ID</p>
                        <p class="text-sm text-white font-mono">
                            {{ config('paymob.integrations.wallet') ?: 'Not set' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-amber-500/10 border border-amber-500/30 rounded-lg">
                <p class="text-sm text-amber-300">
                    Get your Paymob credentials from <a href="https://accept.paymob.com" target="_blank" class="underline">accept.paymob.com</a>
                </p>
            </div>

            <div class="mt-4 p-4 bg-slate-800 rounded-lg">
                <p class="text-xs text-slate-400 mb-2">Required .env variables:</p>
                <pre class="text-xs text-slate-500 overflow-x-auto">PAYMOB_API_KEY=
PAYMOB_SECRET_KEY=
PAYMOB_PUBLIC_KEY=
PAYMOB_INTEGRATION_ID_CARD=
PAYMOB_INTEGRATION_ID_WALLET=
PAYMOB_HMAC_SECRET=</pre>
            </div>
        </x-admin.ui.glass-panel>
    </div>

    <!-- Recent Payments -->
    <x-admin.ui.glass-panel class="mt-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-700/50 bg-white/5">
            <h3 class="font-bold text-white text-lg">Recent Online Payments</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-400">
                <thead class="bg-slate-900/50 uppercase tracking-wider text-xs font-bold text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Method</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Transaction ID</th>
                        <th class="px-6 py-4 text-right">Amount</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($recentPayments as $order)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 font-mono text-blue-400">#{{ $order->order_number }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded bg-slate-700">{{ ucfirst($order->payment_method) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'paid' => 'emerald',
                                    'pending' => 'amber',
                                    'awaiting_payment' => 'blue',
                                    'failed' => 'rose',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded bg-{{ $statusColors[$order->payment_status] ?? 'slate' }}-500/20 text-{{ $statusColors[$order->payment_status] ?? 'slate' }}-400">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">{{ $order->transaction_id ?: '-' }}</td>
                        <td class="px-6 py-4 text-right font-bold text-white">{{ number_format($order->total_amount) }} LE</td>
                        <td class="px-6 py-4 text-slate-500">{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 italic">No online payments yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.ui.glass-panel>
@endsection
