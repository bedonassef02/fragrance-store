@extends('layouts.app')

@section('title', 'Server Error | MOON')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-moon-dark pt-20 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center space-y-8">
        <div>
            <h1 class="text-9xl font-serif text-moon-gold font-bold opacity-20 select-none">500</h1>
            <p class="mt-2 text-3xl font-serif text-white tracking-widest">Something went wrong</p>
            <p class="mt-4 text-gray-400 font-light">We encountered an internal error. Please try again later.</p>
        </div>
        
        <div class="mt-8 flex justify-center">
            <a href="{{ route('home') }}" class="px-8 py-3 bg-moon-gold text-moon-dark font-bold uppercase tracking-widest text-sm hover:bg-white transition-colors transition-transform hover:scale-105 duration-300">
                Return Home
            </a>
        </div>
    </div>
</div>
@endsection
