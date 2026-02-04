@extends('layouts.app')

@section('title', 'Page Not Found | MOON')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cream pt-20 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center space-y-8">
        <div>
            <h1 class="text-9xl font-serif text-accent font-bold opacity-30 select-none">404</h1>
            <p class="mt-2 text-3xl font-serif text-charcoal tracking-widest">Lost in the moonlight?</p>
            <p class="mt-4 text-neutral-500 font-light">The page you are looking for seems to have vanished into the night.</p>
        </div>
        
        <div class="mt-8 flex justify-center gap-4">
            <a href="{{ route('home') }}" class="btn-primary">
                Return Home
            </a>
            <a href="{{ route('shop') }}" class="btn-secondary">
                Shop Now
            </a>
        </div>
    </div>
</div>
@endsection
