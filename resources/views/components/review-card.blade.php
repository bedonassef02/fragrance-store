@props(['review'])

<div class="bg-white/5 border border-white/10 p-6 rounded-sm">
    <div class="flex justify-between items-start mb-4">
        <div>
            <x-static-star-rating :rating="$review->rating" />
            <span class="text-white text-sm font-bold">{{ $review->user->name ?? 'Verified Buyer' }}</span>
        </div>
        <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
    </div>
    <p class="text-gray-400 text-sm leading-relaxed mb-4">{{ $review->comment }}</p>
    @if($review->image_path)
    <img src="{{ $review->image_path }}" class="w-20 h-20 object-cover rounded-sm border border-gray-700 cursor-zoom-in">
    @endif
</div>
