import { updateCartBadge } from './cart-utils';
import apiService from './apiService';
import { showToast, setLoading } from './ui-helpers';

document.addEventListener('DOMContentLoaded', function () {
    const variantsDataEl = document.getElementById('product-variants-data');
    
    // Only run on product pages
    if (!variantsDataEl) return;

    // --- DOM Elements ---
    const capacityContainer = document.getElementById('capacity-container');
    const addToBagBtn = document.getElementById('add-to-bag-btn');
    const mainImage = document.getElementById('main-image');
    const galleryThumbs = document.querySelectorAll('.gallery-thumb');
    const qtyInput = document.getElementById('quantity-input');
    const priceEl = document.getElementById('product-price');
    const selectedCapacityInput = document.getElementById('selected-capacity');
    
    // Quantity Controls
    const qtyMinus = document.getElementById('qty-minus');
    const qtyPlus = document.getElementById('qty-plus');

    // Zoom Controls
    const zoomModal = document.getElementById('zoom-modal');
    const zoomImg = document.getElementById('zoom-img-full');

    // --- State ---
    let state = {
        variants: JSON.parse(variantsDataEl.dataset.variants || '[]'),
        selectedCapacity: null,
        selectedVariant: null,
    };

    // --- UI Functions ---
    const ui = {
        updateCapacityButtons() {
            if (!capacityContainer) return;
            const capBtns = capacityContainer.querySelectorAll('.product-capacity-btn');
            
            capBtns.forEach(btn => {
                const capacity = btn.dataset.capacity;
                const isSelected = capacity === state.selectedCapacity;
                
                if (isSelected) {
                    btn.classList.add('selected');
                } else {
                    btn.classList.remove('selected');
                }
            });
        },

        updatePrice() {
            if (!priceEl || !state.selectedVariant) return;
            priceEl.textContent = state.selectedVariant.price + ' LE';
        },

        updateAddToBagButton() {
            if (!addToBagBtn) return;

            if (!state.selectedVariant) {
                // If we have variants but none selected (shouldn't happen with auto-select), or explicit "Select Option" state
                // However, for single variant items or handled logic, this might differ.
                // For now, if no variant selected, disable.
                
                 // Check if it's a simple product with no variants data vs unselected
                 if (state.variants.length > 0) {
                     addToBagBtn.disabled = true;
                     addToBagBtn.innerText = 'Select Capacity';
                     addToBagBtn.classList.add('opacity-50', 'cursor-not-allowed');
                 } else {
                     // No variants (e.g. simple product, if supported)
                     addToBagBtn.disabled = false;
                     addToBagBtn.innerText = 'Add to Bag';
                     addToBagBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                 }
                return;
            }

            // Update Data ID for direct add logic (if needed elsewhere)
            addToBagBtn.dataset.id = state.selectedVariant.id;

            if (state.selectedVariant.qty === 0) {
                addToBagBtn.disabled = true;
                addToBagBtn.innerText = 'Out of Stock';
                addToBagBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                addToBagBtn.disabled = false;
                addToBagBtn.innerText = 'Add to Bag';
                addToBagBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        },

        updateAll() {
            this.updateCapacityButtons();
            
            // Find selected variant
            if (state.selectedCapacity) {
                state.selectedVariant = state.variants.find(v => v.capacity === state.selectedCapacity);
            }

            this.updatePrice();
            this.updateAddToBagButton();
            
            // Update hidden input
            if (selectedCapacityInput) {
                selectedCapacityInput.value = state.selectedCapacity || '';
            }
        }
    };

    // --- Event Listeners ---
    
    // Capacity Selection
    capacityContainer?.addEventListener('click', (e) => {
        const btn = e.target.closest('.product-capacity-btn');
        if (btn) {
            state.selectedCapacity = btn.dataset.capacity;
            ui.updateAll();
        }
    });

    // Add to Bag
    addToBagBtn?.addEventListener('click', async () => {
        // If variants exist but none selected
        if (state.variants.length > 0 && !state.selectedVariant) {
            showToast('Please select a capacity.', true);
            return;
        }

        // If selected but out of stock
        if (state.selectedVariant && state.selectedVariant.qty === 0) {
            showToast('This item is out of stock.', true);
            return;
        }
        
        // Determine ID to add: Variant ID if exists, described in button's data-id
        const idToAdd = state.selectedVariant ? state.selectedVariant.id : addToBagBtn.dataset.id;
        
        if (!idToAdd) {
             showToast('Error: Product ID not found.', true);
             return;
        }

        setLoading(addToBagBtn, true, 'Adding...', 'Add to Bag');
        const quantity = parseInt(qtyInput.value) || 1;
        
        try {
            const { success, data, error } = await apiService.addToCart(idToAdd, quantity);
            
            if (success) {
                showToast('Item added to bag!');
                updateCartBadge(data.cartCount);
            } else {
                showToast(error || 'Failed to add item', true);
            }
        } catch (err) {
            showToast('An unexpected error occurred.', true);
        } finally {
            setLoading(addToBagBtn, false);
        }
    });

    // Quantity Logic
    qtyMinus?.addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (val > 1) qtyInput.value = val - 1;
    });

    qtyPlus?.addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (qtyInput.max && val >= parseInt(qtyInput.max)) return;
        qtyInput.value = val + 1;
    });

    // Gallery Logic
    galleryThumbs.forEach(thumb => {
        thumb.addEventListener('click', () => {
            const src = thumb.getAttribute('src'); // Use src attribute directly
            mainImage.style.opacity = '0.5';
            setTimeout(() => {
                mainImage.src = src;
                mainImage.style.opacity = '1';
            }, 150);

            galleryThumbs.forEach(t => t.classList.remove('border-moon-gold'));
            thumb.classList.add('border-moon-gold');
        });
    });

    // Zoom Logic
    mainImage?.addEventListener('click', () => {
        if (zoomModal && zoomImg) {
            zoomImg.src = mainImage.src;
            zoomModal.classList.remove('hidden');
            zoomModal.classList.add('flex');
            setTimeout(() => {
                zoomImg.classList.remove('scale-90', 'opacity-0');
            }, 10);
        }
    });

    zoomModal?.addEventListener('click', () => {
        if (zoomImg) {
            zoomImg.classList.add('scale-90', 'opacity-0');
        }
        setTimeout(() => {
            zoomModal.classList.add('hidden');
            zoomModal.classList.remove('flex');
        }, 300);
    });


    // --- Initialization ---
    function initialize() {
        if (state.variants.length > 0) {
            // Auto Select first option
             state.selectedCapacity = state.variants[0].capacity;
        } else {
             // Handle simple product case if needed, or leave null
        }
        ui.updateAll();
    }

    initialize();
});
