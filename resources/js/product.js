import { updateCartBadge } from './cart-utils';
import apiService from './apiService';

document.addEventListener('DOMContentLoaded', function () {
    const variantsDataEl = document.getElementById('product-variants-data');
    if (!variantsDataEl) return;

    // --- DOM Elements ---
    const colorSwatches = document.querySelectorAll('.color-btn');
    const sizeContainer = document.getElementById('size-container');
    const addToBagBtn = document.getElementById('add-to-bag-btn');
    const colorNameDisplay = document.getElementById('selected-color-name');
    const mainImage = document.getElementById('main-image');
    const galleryThumbs = document.querySelectorAll('.gallery-thumb');
    const qtyInput = document.getElementById('quantity-input');
    const toast = document.getElementById('toast');

    // --- State ---
    let state = {
        variants: JSON.parse(variantsDataEl.dataset.variants || '[]'),
        selectedColor: null,
        selectedSize: null,
        selectedVariant: null,
    };

    // --- UI Functions ---
    const ui = {
        updateColorSwatches() {
            colorSwatches.forEach(btn => {
                const isSelected = btn.dataset.color === state.selectedColor;
                btn.classList.toggle('border-moon-gold', isSelected);
                btn.classList.toggle('border-transparent', !isSelected);
            });
            if (colorNameDisplay) colorNameDisplay.textContent = state.selectedColor;
        },
        updateSizeButtons() {
            const sizeBtns = sizeContainer.querySelectorAll('.product-size-btn');
            sizeBtns.forEach(btn => {
                const size = btn.dataset.size;
                const variant = state.variants.find(v => v.size === size && v.color === state.selectedColor);
                const isOutOfStock = !variant || variant.qty === 0;

                btn.disabled = isOutOfStock;
                btn.classList.toggle('opacity-50', isOutOfStock);
                btn.classList.toggle('cursor-not-allowed', isOutOfStock);
                
                const isSelected = size === state.selectedSize;
                btn.classList.toggle('bg-moon-gold', isSelected && !isOutOfStock);
                btn.classList.toggle('text-moon-dark', isSelected && !isOutOfStock);
            });
        },
        updateMainImage() {
            if (!mainImage) return;
            const variantImage = galleryThumbs.length > 0 && Array.from(galleryThumbs).find(thumb => thumb.dataset.color === state.selectedColor);
            if (variantImage) {
                mainImage.src = variantImage.src;
                galleryThumbs.forEach(t => t.classList.remove('border-moon-gold'));
                variantImage.classList.add('border-moon-gold');
            }
        },
        setLoading(isLoading) {
            if (!addToBagBtn) return;
            addToBagBtn.disabled = isLoading;
            addToBagBtn.innerText = isLoading ? 'Adding...' : 'Add to Bag';
        },
        showToast(message, isError = false) {
            if (!toast) return;
            toast.textContent = message;
            toast.className = `fixed bottom-5 right-5 text-white px-6 py-3 rounded-lg shadow-lg transition-all duration-300 ${isError ? 'bg-red-600' : 'bg-green-600'}`;
            toast.classList.remove('translate-y-20', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        },
        updateAll() {
            this.updateColorSwatches();
            this.updateSizeButtons();
            this.updateMainImage();
            // Find and set the currently selected variant object
            state.selectedVariant = state.variants.find(v => v.color === state.selectedColor && v.size === state.selectedSize) || null;
        }
    };

    // --- Event Listeners ---
    colorSwatches.forEach(btn => {
        btn.addEventListener('click', () => {
            state.selectedColor = btn.dataset.color;
            // Find the first available size for this new color and select it
            const firstAvailable = state.variants.find(v => v.color === state.selectedColor && v.qty > 0);
            state.selectedSize = firstAvailable ? firstAvailable.size : null;
            ui.updateAll();
        });
    });

    sizeContainer?.addEventListener('click', (e) => {
        const sizeBtn = e.target.closest('.product-size-btn');
        if (sizeBtn && !sizeBtn.disabled) {
            state.selectedSize = sizeBtn.dataset.size;
            ui.updateAll();
        }
    });

    addToBagBtn?.addEventListener('click', async () => {
        if (!state.selectedVariant) {
            ui.showToast('Please make a valid selection.', true);
            return;
        }
        if (state.selectedVariant.qty === 0) {
            ui.showToast('This item is out of stock.', true);
            return;
        }

        ui.setLoading(true);
        const quantity = parseInt(qtyInput.value) || 1;
        const { success, data, error } = await apiService.addToCart(state.selectedVariant.id, quantity);
        ui.setLoading(false);

        if (success) {
            ui.showToast('Item added to bag!');
            updateCartBadge(data.cartCount);
        } else {
            ui.showToast(error, true);
        }
    });

    // --- New/Restored Gallery & Zoom Logic ---
    const qtyMinus = document.getElementById('qty-minus');
    const qtyPlus = document.getElementById('qty-plus');
    const zoomModal = document.getElementById('zoom-modal');
    const zoomImg = document.getElementById('zoom-img-full');

    qtyMinus?.addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (val > 1) qtyInput.value = val - 1;
    });

    qtyPlus?.addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (qtyInput.max && val >= parseInt(qtyInput.max)) return;
        qtyInput.value = val + 1;
    });

    galleryThumbs.forEach(thumb => {
        thumb.addEventListener('click', () => {
            const src = thumb.dataset.src || thumb.src;
            mainImage.style.opacity = '0.5';
            setTimeout(() => {
                mainImage.src = src;
                mainImage.style.opacity = '1';
            }, 150);

            galleryThumbs.forEach(t => t.classList.remove('border-moon-gold'));
            thumb.classList.add('border-moon-gold');
        });
    });

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
    // --- End of New/Restored Logic ---


    // --- Initialization ---
    function initialize() {
        const inStockVariants = state.variants.filter(v => v.qty > 0);
        if (inStockVariants.length > 0) {
            state.selectedColor = inStockVariants[0].color;
            state.selectedSize = inStockVariants[0].size;
        } else if (state.variants.length > 0) {
            // If all are out of stock, select the first one anyway to show options
            state.selectedColor = state.variants[0].color;
            state.selectedSize = state.variants[0].size;
        }
        ui.updateAll();
    }

    initialize();
});
