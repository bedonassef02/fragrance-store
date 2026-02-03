@extends('layouts.admin')

@section('title', 'Reviews')

@section('header')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-white tracking-tight font-display">Reviews</h1>
        <p class="text-moon-gray-400 mt-1">Moderate customer feedback.</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Toolbar -->
    <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/5 rounded-2xl p-4 flex flex-col md:flex-row gap-4 items-center justify-between">
        <!-- Filter Buttons -->
        <div class="flex bg-black/20 p-1 rounded-xl border border-white/10">
            <a href="{{ route('admin.reviews.index') }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ !request('status') ? 'bg-moon-gold text-moon-dark shadow-lg font-bold' : 'text-moon-gray-400 hover:text-white hover:bg-white/5' }}">
                All
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('status') === 'pending' ? 'bg-moon-gold text-moon-dark shadow-lg font-bold' : 'text-moon-gray-400 hover:text-white hover:bg-white/5' }}">
                Pending
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('status') === 'approved' ? 'bg-moon-gold text-moon-dark shadow-lg font-bold' : 'text-moon-gray-400 hover:text-white hover:bg-white/5' }}">
                Approved
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Reviews Table -->
    <div class="bg-moon-dark/40 backdrop-blur-md rounded-2xl border border-white/5 overflow-hidden ring-1 ring-white/5 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/5 bg-white/5">
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest pl-8">Customer</th>
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Product</th>
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Rating</th>
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest w-1/3">Comment</th>
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-5 text-xs font-bold text-moon-gray-400 uppercase tracking-widest text-right pr-8">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-sm">
                    @forelse($reviews as $review)
                    <tr class="group hover:bg-white/[0.02] transition-colors duration-200">
                         <td class="px-6 py-4 pl-8">
                            @if($review->user)
                                <div class="font-medium text-white">{{ $review->user->name }}</div>
                            @elseif($review->order)
                                <div class="font-medium text-white">{{ $review->order->first_name }} {{ $review->order->last_name }} <span class="text-xs text-moon-gray-500">(Guest)</span></div>
                            @else
                                <div class="text-moon-gray-500">Unknown</div>
                            @endif
                             <div class="text-xs text-moon-gray-500 mt-0.5">{{ $review->created_at->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($review->product)
                                <a href="{{ route('product.show', $review->product->slug) }}" target="_blank" class="flex items-center gap-3 hover:text-moon-gold transition-colors">
                                    <div class="w-10 h-10 rounded bg-white/10 overflow-hidden flex-shrink-0 border border-white/5">
                                        <img src="{{ $review->product->image }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="truncate max-w-[150px] font-medium">{{ $review->product->name }}</div>
                                </a>
                            @else
                                <span class="text-moon-gray-500 italic">Deleted Product</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                           <div class="flex text-moon-gold">
                               @for($i = 1; $i <= 5; $i++)
                                   @if($i <= $review->rating)
                                       <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                   @else
                                       <svg class="w-4 h-4 text-moon-gray-600 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                   @endif
                               @endfor
                           </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-moon-gray-300 text-sm line-clamp-2" title="{{ $review->comment }}">
                                {{ $review->comment ?: 'No comment provided.' }}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            @if($review->is_approved)
                                <span class="px-2.5 py-1 rounded-full border border-emerald-500/20 bg-emerald-500/10 text-emerald-400 text-xs font-bold uppercase tracking-wider">Approved</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full border border-yellow-500/20 bg-yellow-500/10 text-yellow-400 text-xs font-bold uppercase tracking-wider">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right pr-8">
                             <div class="flex justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('admin.reviews.update', $review) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    @if($review->is_approved)
                                        <input type="hidden" name="is_approved" value="0">
                                        <button type="submit" class="p-2 text-moon-gray-400 hover:text-yellow-400 hover:bg-yellow-500/10 rounded-lg transition-all" title="Reject / Hide">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        </button>
                                    @else
                                        <input type="hidden" name="is_approved" value="1">
                                        <button type="submit" class="p-2 text-moon-gray-400 hover:text-emerald-400 hover:bg-emerald-500/10 rounded-lg transition-all" title="Approve">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        </button>
                                    @endif
                                </form>
                                
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete review?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-moon-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all" title="Delete">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                         <td colspan="6" class="px-6 py-32 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mb-6 ring-1 ring-white/10">
                                    <svg class="w-8 h-8 text-moon-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-2 font-display">No Reviews Found</h3>
                                <p class="text-moon-gray-400 mb-8 max-w-sm mx-auto">Customer feedback will appear here.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($reviews->hasPages())
        <div class="px-6 py-4 border-t border-white/5 bg-white/[0.02]">
            {{ $reviews->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
