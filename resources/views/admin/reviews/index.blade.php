@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')
    <x-admin.ui.page-header title="Reviews" description="Moderate customer feedback.">
        <x-slot name="actions">
            <!-- Filter Buttons -->
            <div class="flex bg-slate-800/50 p-1 rounded-lg border border-slate-700">
                <a href="{{ route('admin.reviews.index') }}" 
                   class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ !request('status') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-white' }}">
                    All
                </a>
                <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" 
                   class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ request('status') === 'pending' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-white' }}">
                    Pending
                </a>
                <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" 
                   class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ request('status') === 'approved' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-white' }}">
                    Approved
                </a>
            </div>
        </x-slot>
    </x-admin.ui.page-header>

    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            {{ session('success') }}
        </div>
    @endif

    <x-admin.ui.glass-panel class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-400">
                <thead class="bg-slate-900/50 uppercase tracking-wider text-xs font-bold text-slate-500">
                    <tr>
                        <th class="px-8 py-4">Customer</th>
                        <th class="px-8 py-4">Product</th>
                        <th class="px-8 py-4">Rating</th>
                        <th class="px-8 py-4 w-1/3">Comment</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-white/5 transition-colors group">
                         <td class="px-8 py-4">
                            @if($review->user)
                                <div class="font-medium text-white">{{ $review->user->name }}</div>
                            @elseif($review->order)
                                <div class="font-medium text-white">{{ $review->order->first_name }} {{ $review->order->last_name }} <span class="text-xs text-slate-500">(Guest)</span></div>
                            @else
                                <div class="text-slate-500">Unknown</div>
                            @endif
                             <div class="text-xs opacity-60">{{ $review->created_at->format('M d, Y') }}</div>
                        </td>
                        <td class="px-8 py-4">
                            @if($review->product)
                                <a href="{{ route('product.show', $review->product->slug) }}" target="_blank" class="flex items-center gap-3 hover:text-blue-400 transition-colors">
                                    <div class="w-10 h-10 rounded bg-slate-800 overflow-hidden flex-shrink-0 border border-slate-700">
                                        <img src="{{ $review->product->image }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="truncate max-w-[150px] font-medium">{{ $review->product->name }}</div>
                                </a>
                            @else
                                <span class="text-slate-500 italic">Deleted Product</span>
                            @endif
                        </td>
                        <td class="px-8 py-4">
                           <x-static-star-rating :rating="$review->rating" />
                        </td>
                        <td class="px-8 py-4">
                            <p class="text-slate-300 text-sm line-clamp-2" title="{{ $review->comment }}">
                                {{ $review->comment ?: 'No comment provided.' }}
                            </p>
                            @if($review->image_path)
                                <div class="mt-2">
                                     <a href="{{ asset('storage/' . $review->image_path) }}" target="_blank" class="text-xs text-blue-400 hover:text-blue-300 hover:underline flex items-center gap-1.5 font-medium">
                                         <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                         View Attachment
                                     </a>
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-4">
                            <x-admin.ui.badge :color="$review->is_approved ? 'emerald' : 'amber'" :label="$review->is_approved ? 'Approved' : 'Pending'" />
                        </td>
                        <td class="px-8 py-4 text-right">
                             <div class="flex justify-end gap-2">
                                <form action="{{ route('admin.reviews.update', $review) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    @if($review->is_approved)
                                        <input type="hidden" name="is_approved" value="0">
                                        <x-admin.ui.button type="submit" variant="icon" class="text-amber-400 hover:bg-amber-500/10 hover:text-amber-300" title="Reject / Hide">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                        </x-admin.ui.button>
                                    @else
                                        <input type="hidden" name="is_approved" value="1">
                                        <x-admin.ui.button type="submit" variant="icon" class="text-emerald-400 hover:bg-emerald-500/10 hover:text-emerald-300" title="Approve">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" /></svg>
                                        </x-admin.ui.button>
                                    @endif
                                </form>
                                
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="confirm-delete">
                                    @csrf
                                    @method('DELETE')
                                    <x-admin.ui.button type="submit" variant="icon" class="text-rose-400 hover:bg-rose-500/10 hover:text-rose-300" title="Delete">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </x-admin.ui.button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-12 text-center text-slate-500 italic">No reviews found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($reviews->hasPages())
        <div class="px-8 py-6 border-t border-slate-700/50">
            {{ $reviews->links() }}
        </div>
        @endif
    </x-admin.ui.glass-panel>
@endsection
