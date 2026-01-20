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
    const sizeButtonsContainer = document.getElementById('modal-size-buttons');
    const toast = document.getElementById('toast');

    // --- State ---
    let state = {
        variants: [],
        selectedVariantId: null,
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

    // --- Event Handlers ---
    document.body.addEventListener('click', function(e) {
        if (e.target.matches('.quick-add-btn')) {
            e.preventDefault();
            initModal(e.target);
        }
    });

    closeModalBtn?.addEventListener('click', ui.closeModal.bind(ui));
    backdrop?.addEventListener('click', ui.closeModal.bind(ui));
    confirmBtn?.addEventListener('click', handleConfirmAddToCart);

    // --- Logic ---
    function initModal(button) {
        state.variants = JSON.parse(button.dataset.variants || '[]');
        
        const inStockVariants = state.variants.filter(v => v.qty > 0);

        // If only one variant exists and it's in stock, add directly.
        if (inStockVariants.length === 1 && state.variants.length === 1) {
            handleDirectAddToCart(inStockVariants[0].id);
            return;
        }

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
        const { selectedVariantId } = findSelectedVariant();
        if (!selectedVariantId) {
            ui.showToast('Please make a selection.', true);
            return;
        }
        
        ui.setLoading(true);
        const { success, data, error } = await apiService.addToCart(selectedVariantId, 1);
        ui.setLoading(false);

        if (success) {
            ui.closeModal();
            ui.showToast('Item added to bag!');
            updateCartBadge(data.cartCount);
        } else {
            ui.showToast(error, true);
        }
    }

    function findSelectedVariant() {
        const variant = state.variants.find(v => {
            const colorMatch = state.selectedColor ? v.color === state.selectedColor : true;
            const sizeMatch = state.selectedSize ? v.size === state.selectedSize : true;
            return colorMatch && sizeMatch;
        });
        state.selectedVariantId = variant ? variant.id : null;
        return { selectedVariantId: state.selectedVariantId, variant: variant };
    }

    function renderOptions() {
        sizeButtonsContainer.innerHTML = '';
        const uniqueColors = [...new Set(state.variants.map(v => v.color).filter(Boolean))];
        const uniqueSizes = [...new Set(state.variants.map(v => v.size).filter(s => s && s !== 'One Size'))];

        if (uniqueColors.length > 0) renderColorSwatches(uniqueColors);
        if (uniqueSizes.length > 0) renderSizeButtons(uniqueSizes);
    }

    function renderColorSwatches(colors) {
        const colorsDiv = document.createElement('div');
        colorsDiv.className = 'flex gap-3 mb-6';
        
        const label = document.createElement('p');
        label.className = 'text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide';
        label.innerText = 'Select Color';
        sizeButtonsContainer.appendChild(label);
        
        colors.forEach(color => {
            const isOutOfStock = !state.variants.some(v => v.color === color && v.qty > 0);
            const variant = state.variants.find(v => v.color === color);
            
            const btn = document.createElement('button');
            btn.className = 'w-10 h-10 rounded-full border-2 transition-all relative';
            btn.style.backgroundColor = variant?.color_hex || '#000';
            btn.title = color + (isOutOfStock ? ' (Sold Out)' : '');
            btn.dataset.color = color;

            if (isOutOfStock) {
                btn.disabled = true;
                btn.className += ' opacity-50 cursor-not-allowed';
            } else {
                btn.className += ' hover:border-moon-gold hover:scale-105';
                btn.addEventListener('click', () => {
                    state.selectedColor = color;
                    renderOptions();
                });
            }

            btn.classList.toggle('border-moon-gold', color === state.selectedColor);
            btn.classList.toggle('border-transparent', color !== state.selectedColor);

            colorsDiv.appendChild(btn);
        });
        sizeButtonsContainer.appendChild(colorsDiv);
    }
    
    function renderSizeButtons(sizes) {
        const sizeDiv = document.createElement('div');
        sizeDiv.className = 'flex flex-wrap gap-2';

        const label = document.createElement('p');
        label.className = 'text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide w-full';
        label.innerText = 'Select Size';
        sizeDiv.appendChild(label);
        
        sizes.forEach(size => {
            const isAvailableForColor = state.selectedColor ? state.variants.some(v => v.color === state.selectedColor && v.size === size) : true;
            const variant = state.variants.find(v => v.size === size && (state.selectedColor ? v.color === state.selectedColor : true));
            const isOutOfStock = !variant || variant.qty === 0;
            const isDisabled = !isAvailableForColor || isOutOfStock;

            const btn = document.createElement('button');
            btn.className = 'size-btn w-12 h-12 border text-gray-400 font-bold transition-all rounded-sm relative';
            btn.innerText = size;

            if (isDisabled) {
                btn.disabled = true;
                btn.className += ' opacity-50 cursor-not-allowed bg-gray-800 border-gray-700';
            } else {
                btn.className += ' border-gray-600 hover:border-moon-gold hover:text-white';
                btn.addEventListener('click', () => {
                    state.selectedSize = size;
                    renderOptions(); 
                });
            }
            
            if (size === state.selectedSize && !isDisabled) {
                btn.classList.add('bg-moon-gold', 'text-black', 'border-moon-gold');
            }

            sizeDiv.appendChild(btn);
        });
        sizeButtonsContainer.appendChild(sizeDiv);
    }
});
