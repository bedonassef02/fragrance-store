<div id="quick-add-modal" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-cream border border-neutral-200 p-8 z-50 w-full max-w-md hidden opacity-0 transition-all duration-300 scale-95 shadow-2xl">
    <button id="close-modal" class="absolute top-4 right-4 text-neutral-400 hover:text-charcoal transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    
    <h3 id="modal-product-title" class="text-2xl font-serif text-charcoal mb-2">Product Name</h3>
    <p id="modal-product-price" class="text-accent font-medium mb-6">0 LE</p>
    
    <div id="modal-options-container" class="mb-8">
        <!-- JS will render capacity options here -->
    </div>

    <button id="confirm-add-to-bag" class="w-full btn-primary">
        Add to Bag
    </button>
</div>

<!-- Toast Notification (overwritten by JS in ui-helpers.js, but fallback HTML here) -->
<div id="toast" class="fixed bottom-8 right-8 bg-charcoal text-cream px-6 py-4 shadow-2xl transform translate-y-20 opacity-0 transition-all duration-500 z-50 flex items-center gap-3 font-medium text-sm tracking-wide">
    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span>Item added to bag!</span>
</div>
