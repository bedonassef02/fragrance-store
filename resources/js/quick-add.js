import { updateCartBadge } from './cart-utils';
import apiService from './apiService';
import { showToast, toggleModal, setLoading } from './ui-helpers';

document.addEventListener('DOMContentLoaded', function () {
    const modalId = 'quick-add-modal';
    const modal = document.getElementById(modalId);
    if (!modal) return;

    // --- DOM Elements ---
    const backdrop = document.getElementById('backdrop');
    const closeModalBtn = document.getElementById('close-modal');
    const modalTitle = document.getElementById('modal-product-title');
    const modalPrice = document.getElementById('modal-product-price');
    const confirmBtn = document.getElementById('confirm-add-to-bag');
    const optionsContainer = document.getElementById('modal-options-container');

    // --- State ---
    let state = {
        variants: [],
        selectedColor: null,
        selectedSize: null,
    };

    // --- UI Functions ---
    const ui = {
        openModal(title, price) {
            modalTitle.textContent = title;
            modalPrice.textContent = price;
            toggleModal(modalId, true);
        },
        closeModal() {
            toggleModal(modalId, false);
        },
    };

    // --- Event Listeners ---
    document.body.addEventListener('click', function (e) {
        const quickAddButton = e.target.closest('.quick-add-btn');
        if (quickAddButton) {
            e.preventDefault();
            initModal(quickAddButton);
        }
    });

    closeModalBtn?.addEventListener('click', ui.closeModal);
    backdrop?.addEventListener('click', ui.closeModal);
    confirmBtn?.addEventListener('click', handleConfirmAddToCart);

    // --- Main Logic ---
    function initModal(button) {
        state.variants = JSON.parse(button.dataset.variants || '[]');
        state.selectedColor = null;
        state.selectedSize = null;

        const inStockVariants = state.variants.filter(v => v.qty > 0);

        // If there's only one variant total and it's in stock, add it directly without showing the modal.
        if (state.variants.length === 1 && inStockVariants.length === 1) {
            handleDirectAddToCart(inStockVariants[0].id);
            return;
        }

        // --- Default Selection Logic ---
        if (inStockVariants.length > 0) {
            const firstAvailable = inStockVariants[0];
            state.selectedColor = firstAvailable.color;
            state.selectedSize = firstAvailable.size;
        } else if (state.variants.length > 0) {
            // Fallback for out of stock - select first to show options
            state.selectedColor = state.variants[0].color;
            state.selectedSize = state.variants[0].size;
        }

        ui.openModal(button.dataset.name, button.dataset.price);
        renderOptions();
    }

    async function handleDirectAddToCart(variantId) {
        showToast('Adding to bag...');
        const { success, data, error } = await apiService.addToCart(variantId, 1);
        if (success) {
            showToast('Item added to bag!');
            updateCartBadge(data.cartCount);
        } else {
            showToast(error, true);
        }
    }

    async function handleConfirmAddToCart() {
        const uniqueColors = [...new Set(state.variants.map(v => v.color).filter(Boolean))];
        const uniqueSizes = [...new Set(state.variants.map(v => v.size).filter(v => v && v !== 'One Size'))];

        if (uniqueColors.length > 0 && !state.selectedColor) {
            showToast('Please select a color.', true);
            return;
        }
        if (uniqueSizes.length > 0 && !state.selectedSize) {
            showToast('Please select a size.', true);
            return;
        }

        const selectedVariant = state.variants.find(v => {
            const colorMatch = uniqueColors.length === 0 || v.color === state.selectedColor;
            const sizeMatch = uniqueSizes.length === 0 || v.size === state.selectedSize;
            return colorMatch && sizeMatch;
        });

        if (!selectedVariant) {
            showToast('This combination is not available.', true);
            return;
        }

        if (selectedVariant.qty <= 0) {
            showToast('This item is out of stock.', true);
            return;
        }

        setLoading(confirmBtn, true, 'Adding...', 'Add to Bag');
        const { success, data, error } = await apiService.addToCart(selectedVariant.id, 1);
        setLoading(confirmBtn, false);

        if (success) {
            ui.closeModal();
            showToast('Item added to bag!');
            updateCartBadge(data.cartCount);
        } else {
            showToast(error, true);
        }
    }

    function renderOptions() {
        optionsContainer.innerHTML = '';
        const uniqueColors = [...new Set(state.variants.map(v => v.color).filter(Boolean))];
        const uniqueSizes = [...new Set(state.variants.map(v => v.size).filter(v => v && v !== 'One Size'))];

        if (uniqueColors.length > 0) {
            optionsContainer.appendChild(createColorSwatches(uniqueColors));
        }
        if (uniqueSizes.length > 0) {
            optionsContainer.appendChild(createSizeButtons(uniqueSizes));
        }
    }

    function createColorSwatches(colors) {
        const container = document.createElement('div');
        container.innerHTML = `<p class="text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Select Color</p>`;
        const swatchesDiv = document.createElement('div');
        swatchesDiv.className = 'flex gap-3 mb-6';

        colors.forEach(color => {
            const isOutOfStock = !state.variants.some(v => v.color === color && v.qty > 0);
            const variant = state.variants.find(v => v.color === color);

            const btn = document.createElement('button');
            btn.className = 'w-10 h-10 rounded-full border-2 transition-all relative disabled:opacity-50 disabled:cursor-not-allowed hover:scale-105';
            btn.style.backgroundColor = variant?.color_hex || '#000';
            btn.title = color + (isOutOfStock ? ' (Sold Out)' : '');
            btn.dataset.color = color;
            // Don't disable color buttons completely, just style them? 
            // Original code disabled them. Let's keep that for now but usually you want to see out of stock colors.
            // Actually original code: btn.disabled = isOutOfStock; 
            // I'll keep it.
            btn.disabled = isOutOfStock;

            btn.addEventListener('click', () => {
                state.selectedColor = color;
                // If the current size is not available with the new color, reset it.
                const isSizeAvailable = state.variants.some(v => v.color === state.selectedColor && v.size === state.selectedSize && v.qty > 0);
                if (!isSizeAvailable) {
                    // Try to auto-select first available size
                    const firstAvailSize = state.variants.find(v => v.color === state.selectedColor && v.qty > 0);
                    state.selectedSize = firstAvailSize ? firstAvailSize.size : null;
                }
                renderOptions();
            });

            btn.classList.toggle('border-moon-gold', color === state.selectedColor);
            btn.classList.toggle('border-transparent', color !== state.selectedColor);
            swatchesDiv.appendChild(btn);
        });
        container.appendChild(swatchesDiv);
        return container;
    }

    function createSizeButtons(sizes) {
        const container = document.createElement('div');
        container.innerHTML = `<p class="text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Select Size</p>`;
        const sizesDiv = document.createElement('div');
        sizesDiv.className = 'flex flex-wrap gap-2';

        sizes.forEach(size => {
            // Check if this size is available for the selected color
            const isAvailableForColor = !state.selectedColor || state.variants.some(v => v.color === state.selectedColor && v.size === size && v.qty > 0);

            // Check if this size exists at all (global stock check for size?) 
            // Original Logic: variantForStockCheck = find(v.size == size && (!selectedColor || v.color == selectedColor))
            // The logic should be: Is there a variant with (Selected Color + This Size) that has Qty > 0?
            // If no color selected is yet, is there ANY variant with This Size and Qty > 0?

            const variant = state.variants.find(v => v.size === size && (!state.selectedColor || v.color === state.selectedColor));
            const isOutOfStock = !variant || variant.qty <= 0;

            // If we strictly enforce color selection first, sizes might look disabled until color is picked.
            // But we auto-select color usually.

            const isDisabled = isOutOfStock;

            const btn = document.createElement('button');
            btn.className = 'size-btn w-12 h-12 border text-gray-400 font-bold transition-all rounded-sm relative disabled:opacity-50 disabled:cursor-not-allowed';
            btn.innerText = size;
            btn.disabled = isDisabled;

            btn.addEventListener('click', () => {
                state.selectedSize = size;
                renderOptions();
            });

            if (size === state.selectedSize && !isDisabled) {
                btn.classList.add('bg-moon-gold', 'text-black', 'border-moon-gold');
                btn.classList.remove('border-gray-600', 'text-gray-400');
            } else {
                btn.classList.add('border-gray-600', 'hover:border-moon-gold', 'hover:text-white');
            }
            sizesDiv.appendChild(btn);
        });
        container.appendChild(sizesDiv);
        return container;
    }
});
