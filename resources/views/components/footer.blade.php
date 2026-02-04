<footer class="bg-neutral-100 border-t border-neutral-200 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <!-- Brand -->
            <div class="md:col-span-1">
                <a href="{{ route('home') }}" class="block">
                    <span class="text-xl font-serif tracking-[0.2em] text-charcoal block mb-2">MOON</span>
                    <span class="text-[10px] uppercase tracking-[0.2em] text-neutral-500">Fragrances</span>
                </a>
                <p class="text-neutral-600 text-sm leading-relaxed mt-6">
                    Curated scents that tell your story. Handcrafted in Egypt.
                </p>
                <div class="flex space-x-4 mt-6">
                    <a href="#" class="text-neutral-500 hover:text-charcoal transition-colors" aria-label="Instagram">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-neutral-500 hover:text-charcoal transition-colors" aria-label="Facebook">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Shop Links -->
            <div>
                <h4 class="text-charcoal font-medium mb-6 uppercase tracking-[0.15em] text-xs">Shop</h4>
                <ul class="space-y-3 text-sm text-neutral-600">
                    <li><a href="{{ route('shop') }}" class="hover:text-charcoal transition-colors">New Arrivals</a></li>
                    <li><a href="{{ route('shop') }}" class="hover:text-charcoal transition-colors">Bestsellers</a></li>
                    <li><a href="{{ route('collections') }}" class="hover:text-charcoal transition-colors">Collections</a></li>
                    <li><a href="{{ route('shop') }}" class="hover:text-charcoal transition-colors">All Fragrances</a></li>
                </ul>
            </div>

            <!-- Help Links -->
            <div>
                <h4 class="text-charcoal font-medium mb-6 uppercase tracking-[0.15em] text-xs">Help</h4>
                <ul class="space-y-3 text-sm text-neutral-600">
                    <li><a href="#" class="hover:text-charcoal transition-colors">Shipping & Returns</a></li>
                    <li><a href="#" class="hover:text-charcoal transition-colors">FAQ</a></li>
                    <li><a href="#" class="hover:text-charcoal transition-colors">Contact Us</a></li>
                    <li><a href="#" class="hover:text-charcoal transition-colors">Track Order</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="text-charcoal font-medium mb-6 uppercase tracking-[0.15em] text-xs">Stay in Touch</h4>
                <p class="text-neutral-600 text-sm mb-4">Subscribe for exclusive offers and new arrivals.</p>
                <form class="flex border-b border-neutral-300 pb-2">
                    <input type="email" placeholder="Your email" class="bg-transparent w-full text-charcoal placeholder-neutral-400 focus:outline-none text-sm">
                    <button type="submit" class="text-charcoal text-xs uppercase tracking-wider font-medium hover:text-accent transition-colors ml-2">Join</button>
                </form>
            </div>
        </div>

        <div class="border-t border-neutral-200 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-neutral-500">
            <p>&copy; {{ date('Y') }} Moon Fragrances. All rights reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-charcoal transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-charcoal transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
