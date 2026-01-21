import { updateCartBadge } from './cart-utils';
import apiService from './apiService';
import { showToast, setLoading } from './ui-helpers';

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
        updateGalleryAndMainImage() {
            if (!mainImage) return;

            // 1. Filter Thumbnails
            const allThumbs = Array.from(galleryThumbs);
            let firstVisibleThumb = null;

            allThumbs.forEach(thumb => {
                const thumbColor = thumb.dataset.color;
                // Show thumb if:
                // a) No color selected
                // b) Thumb is 'all' (main image usually)
                // c) Thumb matches selected color
                const shouldShow = !state.selectedColor || thumbColor === 'all' || thumbColor === state.selectedColor;

                thumb.style.display = shouldShow ? 'block' : 'none';

                if (shouldShow && !firstVisibleThumb) {
                    firstVisibleThumb = thumb;
                }
            });

            // 2. Update Main Image if needed
            // If the currently displayed main image doesn't match the selected color (and isn't 'all'), switch it
            const currentMainColor = mainImage.dataset.currentColor;
            if (state.selectedColor && currentMainColor !== 'all' && currentMainColor !== state.selectedColor) {
                // Find the first image for this color
                const colorImage = allThumbs.find(t => t.dataset.color === state.selectedColor);
                if (colorImage) {
                    mainImage.src = colorImage.src;
                    // Update active state
                    allThumbs.forEach(t => t.classList.remove('border-moon-gold', 'border-transparent'));
                    allThumbs.forEach(t => t.classList.add('border-transparent'));
                    colorImage.classList.remove('border-transparent');
                    colorImage.classList.add('border-moon-gold');
                }
            } else if (!state.selectedColor && firstVisibleThumb) {
                // Reset to first if cleared
                // Optional: decide if we want to reset main image when color is deselected
            }
        },
        updateAll() {
            this.updateColorSwatches();
            this.updateSizeButtons();

            // Replaced updateMainImage with more comprehensive gallery update
            this.updateGalleryAndMainImage();

            // Find and set the currently selected variant object
            state.selectedVariant = state.variants.find(v => v.color === state.selectedColor && v.size === state.selectedSize) || null;
            this.updateAddToBagButton();
        },
        updateAddToBagButton() {
            if (!addToBagBtn) return;

            if (!state.selectedVariant) {
                addToBagBtn.disabled = true;
                addToBagBtn.innerText = 'Select Option';
                addToBagBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }

            if (state.selectedVariant.qty === 0) {
                addToBagBtn.disabled = true;
                addToBagBtn.innerText = 'Out of Stock';
                addToBagBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                addToBagBtn.disabled = false;
                addToBagBtn.innerText = 'Add to Bag';
                addToBagBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
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
            showToast('Please make a valid selection.', true);
            return;
        }
        if (state.selectedVariant.qty === 0) {
            showToast('This item is out of stock.', true);
            return;
        }

        setLoading(addToBagBtn, true, 'Adding...', 'Add to Bag');
        const quantity = parseInt(qtyInput.value) || 1;
        const { success, data, error } = await apiService.addToCart(state.selectedVariant.id, quantity);
        setLoading(addToBagBtn, false);

        if (success) {
            showToast('Item added to bag!');
            updateCartBadge(data.cartCount);
        } else {
            showToast(error, true);
        }
    });

    // --- Gallery & Zoom Logic ---
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
