<footer class="bg-moon-dark border-t border-white/10 pt-20 pb-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            <!-- Brand -->
            <div class="md:col-span-1">
                <span class="text-2xl font-serif font-bold tracking-widest text-white block mb-6">MOON</span>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    Exquisite Arabian fashion for the sophisticated woman. Designed in Dubai.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-moon-gold transition-colors"><span class="sr-only">Instagram</span><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></path></svg></a>
                </div>
            </div>

            <!-- Links -->
            <div>
                <h4 class="text-white font-serif mb-6 uppercase tracking-widest text-xs">Shop</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li><a href="{{ route('shop') }}" class="hover:text-moon-gold transition-colors">New Arrivals</a></li>
                    <li><a href="{{ route('shop') }}" class="hover:text-moon-gold transition-colors">Bestsellers</a></li>
                    <li><a href="{{ route('shop') }}" class="hover:text-moon-gold transition-colors">Abayas</a></li>
                    <li><a href="{{ route('shop') }}" class="hover:text-moon-gold transition-colors">Bags & Accessories</a></li>
                </ul>
            </div>

            <!-- Links -->
            <div>
                <h4 class="text-white font-serif mb-6 uppercase tracking-widest text-xs">Client Services</h4>
                <ul class="space-y-3 text-sm text-gray-400">

                    <li><a href="#" class="hover:text-moon-gold transition-colors">Delivery & Returns</a></li>
                    <li><a href="#" class="hover:text-moon-gold transition-colors">Size Guide</a></li>
                    <li><a href="#" class="hover:text-moon-gold transition-colors">Book an Appointment</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="text-white font-serif mb-6 uppercase tracking-widest text-xs">The Newsletter</h4>
                <p class="text-gray-400 text-sm mb-4">Be the first to know about new collections and exclusive events.</p>
                <form class="flex border-b border-gray-700 pb-2">
                    <input type="email" placeholder="Your email address" class="bg-transparent w-full text-white placeholder-gray-500 focus:outline-none text-sm">
                    <button type="submit" class="text-moon-gold text-sm uppercase tracking-wider font-bold hover:text-white transition-colors">Join</button>
                </form>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500">
            <p>&copy; {{ date('Y') }} Moon Fashion. All rights reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-gray-300">Privacy Policy</a>
                <a href="#" class="hover:text-gray-300">Terms & Conditions</a>
            </div>
        </div>
    </div>
</footer>
