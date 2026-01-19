<div id="quick-add-modal" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-moon-dark border border-gray-700 p-8 z-50 w-full max-w-md hidden opacity-0 transition-all duration-300 scale-95 shadow-2xl">
    <button id="close-modal" class="absolute top-4 right-4 text-gray-400 hover:text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    
    <h3 id="modal-product-title" class="text-2xl font-serif text-white mb-2">Product Name</h3>
    <p id="modal-product-price" class="text-moon-gold font-bold mb-6">0 LE</p>
    
    <div class="mb-8">
        <label class="block text-xs uppercase tracking-widest text-gray-400 mb-3">Select Size</label>
        <div class="flex gap-3">
            <button class="size-btn w-10 h-10 border border-gray-600 text-gray-400 hover:border-moon-gold hover:text-white transition-colors focus:bg-moon-gold focus:text-black focus:border-moon-gold">S</button>
            <button class="size-btn w-10 h-10 border border-gray-600 text-gray-400 hover:border-moon-gold hover:text-white transition-colors focus:bg-moon-gold focus:text-black focus:border-moon-gold">M</button>
            <button class="size-btn w-10 h-10 border border-gray-600 text-gray-400 hover:border-moon-gold hover:text-white transition-colors focus:bg-moon-gold focus:text-black focus:border-moon-gold">L</button>
            <button class="size-btn w-10 h-10 border border-gray-600 text-gray-400 hover:border-moon-gold hover:text-white transition-colors focus:bg-moon-gold focus:text-black focus:border-moon-gold">XL</button>
        </div>
    </div>

    <button id="confirm-add-to-bag" class="w-full bg-moon-gold text-moon-dark font-bold uppercase tracking-widest py-4 hover:bg-white transition-colors">
        Add to Bag
    </button>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-8 right-8 bg-white text-black px-6 py-4 shadow-2xl transform translate-y-20 opacity-0 transition-all duration-500 z-50 flex items-center gap-3 border-l-4 border-moon-gold">
    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <div>
        <h4 class="font-bold text-sm uppercase tracking-wider">Added to Bag</h4>
        <p class="text-xs text-gray-500">The item has been added to your cart.</p>
    </div>
</div>
