@extends('layouts.app')

@section('title', 'Review Your Items | MOON')

@section('content')
<div class="pt-32 pb-24 bg-cream min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <span class="text-accent text-xs uppercase tracking-[0.2em] mb-4 block animate-fadeInUp">Order #{{ $order->order_number }}</span>
            <h1 class="text-3xl md:text-5xl font-serif text-charcoal mb-6 animate-fadeInUp" style="animation-delay: 0.1s">How Was Your Experience?</h1>
            <p class="text-neutral-600 font-light text-lg max-w-xl mx-auto animate-fadeInUp" style="animation-delay: 0.2s">
                Your feedback helps us create better luxury experiences. Please rate the items from your recent purchase.
            </p>
        </div>

        <form action="{{ route('reviews.store', $order->id) }}?signature={{ request()->query('signature') }}" method="POST" enctype="multipart/form-data" class="space-y-12">
            @csrf

            @foreach($order->items as $item)
            @php 
                $product = $item->variant->product;
                $variant = $item->variant;
            @endphp
            <div class="bg-neutral-50 border border-neutral-200 p-6 md:p-8 animate-fadeInUp" style="animation-delay: 0.3s">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Product Image -->
                    <div class="w-full md:w-48 flex-shrink-0">
                        <div class="aspect-[3/4] relative overflow-hidden bg-neutral-100">
                             <img src="{{ $product->image }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                        </div>
                        <div class="mt-4 text-center md:text-left">
                            <h3 class="text-charcoal font-serif text-lg leading-tight">{{ $product->name }}</h3>
                            <p class="text-accent text-xs uppercase tracking-widest mt-1">{{ $variant->color->name ?? '' }} / {{ $variant->size }}</p>
                        </div>
                    </div>

                    <!-- Review Inputs -->
                    <div class="flex-1 space-y-6">
                        <input type="hidden" name="reviews[{{ $product->id }}][product_id]" value="{{ $product->id }}">

                        <!-- Star Rating -->
                        <div>
                            <label class="block text-neutral-600 text-xs uppercase tracking-widest mb-3">Rating</label>
                            <x-forms.star-rating name="reviews[{{ $product->id }}][rating]" idPrefix="rating-{{ $product->id }}" />
                        </div>

                        <!-- Comment -->
                        <div>
                            <label class="block text-neutral-600 text-xs uppercase tracking-widest mb-3">Your Review</label>
                            <textarea name="reviews[{{ $product->id }}][comment]" rows="4" class="w-full bg-white border border-neutral-300 text-charcoal p-4 focus:border-charcoal focus:outline-none transition-colors placeholder-neutral-400" placeholder="Tell us what you liked about this fragrance..."></textarea>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-neutral-600 text-xs uppercase tracking-widest mb-3">Add a Photo (Optional)</label>
                            <div class="relative">
                                <input type="file" name="reviews[{{ $product->id }}][image]" accept="image/*" class="w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-xs file:font-medium file:bg-neutral-200 file:text-charcoal hover:file:bg-neutral-300 transition-all cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Actions -->
            <div class="flex justify-end pt-8 border-t border-neutral-200">
                <button type="submit" class="btn-primary">
                    Submit Reviews
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
