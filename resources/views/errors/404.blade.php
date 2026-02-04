@extends('layouts.app')

@section('title', 'Page Not Found | MOON')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-moon-dark pt-20 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center space-y-8">
        <div>
            <h1 class="text-9xl font-serif text-moon-gold font-bold opacity-20 select-none">404</h1>
            <p class="mt-2 text-3xl font-serif text-white tracking-widest">Lost in the moonlight?</p>
            <p class="mt-4 text-gray-400 font-light">The page you are looking for seems to have vanished into the night.</p>
        </div>
        
        <div class="mt-8 flex justify-center gap-4">
            <a href="{{ route('home') }}" class="px-8 py-3 bg-moon-gold text-moon-dark font-bold uppercase tracking-widest text-sm hover:bg-white transition-colors transition-transform hover:scale-105 duration-300">
                Return Home
            </a>
            <a href="{{ route('shop') }}" class="px-8 py-3 border border-white/20 text-white font-bold uppercase tracking-widest text-sm hover:border-moon-gold hover:text-moon-gold transition-colors duration-300">
                Shop Now
            </a>
        </div>
    </div>
</div>
@endsection
