import { updateCartBadge } from './cart-utils';
import apiService from './apiService';

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('quick-add-modal');
    if (!modal) return;

    // --- DOM Elements ---
    const backdrop = document.getElementById('backdrop');
    const closeModalBtn = document.getElementById('close-modal');
    const modalTitle = document.getElementById('modal-product-title');
    const modalPrice = document.getElementById('modal-product-price');
    const confirmBtn = document.getElementById('confirm-add-to-bag');
    const optionsContainer = document.getElementById('modal-options-container');
    const toast = document.getElementById('toast');

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
            modal.classList.remove('hidden');
            this.showBackdrop();
            setTimeout(() => modal.classList.add('opacity-100', 'scale-100'), 10);
        },
        closeModal() {
            modal.classList.remove('opacity-100', 'scale-100');
            this.hideBackdrop();
            setTimeout(() => modal.classList.add('hidden'), 300);
        },
        showBackdrop() {
            if (!backdrop) return;
            backdrop.classList.remove('hidden');
            setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
        },
        hideBackdrop() {
            if (!backdrop) return;
            backdrop.classList.add('opacity-0');
            setTimeout(() => backdrop.classList.add('hidden'), 300);
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
        setLoading(isLoading) {
            if (!confirmBtn) return;
            confirmBtn.disabled = isLoading;
            confirmBtn.innerText = isLoading ? 'Adding...' : 'Add to Bag';
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

    closeModalBtn?.addEventListener('click', ui.closeModal.bind(ui));
    backdrop?.addEventListener('click', ui.closeModal.bind(ui));
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

        // --- New Default Selection Logic ---
        if (inStockVariants.length > 0) {
            const firstAvailable = inStockVariants[0];
            state.selectedColor = firstAvailable.color;
            state.selectedSize = firstAvailable.size;
        }
        // --- End of New Logic ---

        ui.openModal(button.dataset.name, button.dataset.price);
        renderOptions();
    }

    async function handleDirectAddToCart(variantId) {
        ui.showToast('Adding to bag...');
        const { success, data, error } = await apiService.addToCart(variantId, 1);
        if (success) {
            ui.showToast('Item added to bag!');
            updateCartBadge(data.cartCount);
        } else {
            ui.showToast(error, true);
        }
    }

    async function handleConfirmAddToCart() {
        const uniqueColors = [...new Set(state.variants.map(v => v.color).filter(Boolean))];
        const uniqueSizes = [...new Set(state.variants.map(v => v.size).filter(v => v && v !== 'One Size'))];

        if (uniqueColors.length > 0 && !state.selectedColor) {
            ui.showToast('Please select a color.', true);
            return;
        }
        if (uniqueSizes.length > 0 && !state.selectedSize) {
            ui.showToast('Please select a size.', true);
            return;
        }

        const selectedVariant = state.variants.find(v => {
            const colorMatch = uniqueColors.length === 0 || v.color === state.selectedColor;
            const sizeMatch = uniqueSizes.length === 0 || v.size === state.selectedSize;
            return colorMatch && sizeMatch;
        });

        if (!selectedVariant) {
            ui.showToast('This combination is not available.', true);
            return;
        }

        ui.setLoading(true);
        const { success, data, error } = await apiService.addToCart(selectedVariant.id, 1);
        ui.setLoading(false);

        if (success) {
            ui.closeModal();
            ui.showToast('Item added to bag!');
            updateCartBadge(data.cartCount);
        } else {
            ui.showToast(error, true);
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
            btn.disabled = isOutOfStock;
            
            btn.addEventListener('click', () => {
                state.selectedColor = color;
                // If the current size is not available with the new color, reset it.
                const isSizeAvailable = state.variants.some(v => v.color === state.selectedColor && v.size === state.selectedSize && v.qty > 0);
                if (!isSizeAvailable) {
                    state.selectedSize = null;
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
            const isAvailableForColor = !state.selectedColor || state.variants.some(v => v.color === state.selectedColor && v.size === size);
            const variantForStockCheck = state.variants.find(v => v.size === size && (!state.selectedColor || v.color === state.selectedColor));
            const isOutOfStock = !variantForStockCheck || variantForStockCheck.qty === 0;
            const isDisabled = !isAvailableForColor || isOutOfStock;

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
