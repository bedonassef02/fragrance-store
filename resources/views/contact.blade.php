@extends('layouts.app')

@section('title', 'Contact Us | MOON')

@section('content')
    <div class="pt-44 pb-24 bg-moon-dark min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-5xl font-serif text-white mb-16 text-center">Get in Touch</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                <!-- Info -->
                <div>
                    <h2 class="text-2xl font-serif text-white mb-8">Visit Our Atelier</h2>
                    <div class="space-y-6 text-gray-300">
                        <p>
                            <span class="block text-moon-gold text-xs uppercase tracking-widest font-bold mb-1">Address</span>
                            123 Fashion Avenue, Design District<br>
                            Dubai, UAE
                        </p>
                        <p>
                            <span class="block text-moon-gold text-xs uppercase tracking-widest font-bold mb-1">Email</span>
                            concierge@moon-fashion.com
                        </p>
                        <p>
                            <span class="block text-moon-gold text-xs uppercase tracking-widest font-bold mb-1">Phone</span>
                            +971 4 123 4567
                        </p>
                        <p>
                            <span class="block text-moon-gold text-xs uppercase tracking-widest font-bold mb-1">Hours</span>
                            Monday - Saturday: 10am - 8pm<br>
                            Sunday: Closed
                        </p>
                    </div>

                    <div class="mt-12 h-64 bg-gray-800 relative">
                        <!-- Map placeholder -->
                        <div class="absolute inset-0 flex items-center justify-center text-gray-500">
                            [Map Integration Placeholder]
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="bg-white/5 p-8 md:p-12 rounded-sm border border-white/10">
                    <h2 class="text-2xl font-serif text-white mb-8">Send an Inquiry</h2>
                    <form class="space-y-6">
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2">Name</label>
                            <input type="text" class="w-full bg-black/20 border border-gray-700 text-white px-4 py-3 focus:border-moon-gold focus:outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2">Email</label>
                            <input type="email" class="w-full bg-black/20 border border-gray-700 text-white px-4 py-3 focus:border-moon-gold focus:outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2">Subject</label>
                            <select class="w-full bg-black/20 border border-gray-700 text-white px-4 py-3 focus:border-moon-gold focus:outline-none transition-colors">
                                <option>General Inquiry</option>
                                <option>Order Support</option>
                                <option>Wholesale</option>
                                <option>Press</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2">Message</label>
                            <textarea rows="4" class="w-full bg-black/20 border border-gray-700 text-white px-4 py-3 focus:border-moon-gold focus:outline-none transition-colors"></textarea>
                        </div>
                        <button type="button" class="w-full bg-moon-gold text-moon-dark font-bold uppercase tracking-widest py-4 hover:bg-white transition-colors">
                            SendMessage
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
