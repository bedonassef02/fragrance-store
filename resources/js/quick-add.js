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
        selectedCapacity: null,
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
        state.selectedCapacity = null;

        const inStockVariants = state.variants.filter(v => v.qty > 0);

        // 1. Direct Add: If only one variant exists and is in stock
        // The user asked: "if it does not have options... dont show pop up"
        if (state.variants.length === 1 && inStockVariants.length === 1) {
            handleDirectAddToCart(inStockVariants[0].id);
            return;
        }

        // If single variant but out of stock?
        if (state.variants.length === 1 && inStockVariants.length === 0) {
            showToast('This item is out of stock.', true);
            return;
        }

        // 2. Open Modal for Multiple Options
        // Auto-select first available if desired, OR leave null to force choice.
        // User asked: "make the user choose one". So we will NOT auto-select.

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
        if (!state.selectedCapacity) {
            showToast('Please select a capacity.', true);
            return;
        }

        const selectedVariant = state.variants.find(v => v.capacity === state.selectedCapacity);

        if (!selectedVariant) {
            showToast('Invalid selection.', true);
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

        // Extract Unique Capacities
        const uniqueCapacities = [...new Set(state.variants.map(v => v.capacity))];

        if (uniqueCapacities.length > 0) {
            optionsContainer.appendChild(createCapacityButtons(uniqueCapacities));
        }
    }

    function createCapacityButtons(capacities) {
        const container = document.createElement('div');
        container.innerHTML = `<p class="text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Select Capacity</p>`;
        const buttonsDiv = document.createElement('div');
        buttonsDiv.className = 'flex flex-wrap gap-2';

        capacities.forEach(capacity => {
            const variant = state.variants.find(v => v.capacity === capacity);
            const isOutOfStock = !variant || variant.qty <= 0;
            const isSelected = state.selectedCapacity === capacity;

            const btn = document.createElement('button');
            // product-capacity-btn class can be reused if global, otherwise duplicate styles
            btn.className = `min-w-[4rem] px-4 py-2 border text-sm font-bold transition-all rounded-sm relative ${isOutOfStock ? 'opacity-50 cursor-not-allowed border-gray-800 text-gray-600' :
                    isSelected ? 'bg-moon-gold text-moon-dark border-moon-gold' : 'border-gray-600 text-gray-400 hover:border-moon-gold hover:text-white'
                }`;

            btn.innerText = capacity;
            btn.disabled = isOutOfStock;

            if (!isOutOfStock) {
                btn.addEventListener('click', () => {
                    state.selectedCapacity = capacity;

                    // Update Price in Modal
                    if (variant) {
                        modalPrice.textContent = variant.price;
                    }

                    renderOptions(); // Re-render to update active classes
                });
            }

            buttonsDiv.appendChild(btn);
        });
        container.appendChild(buttonsDiv);
        return container;
    }
});
