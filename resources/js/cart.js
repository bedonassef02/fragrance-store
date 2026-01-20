import apiService from './apiService';
import { updateCartBadge } from './cart-utils';

document.addEventListener('DOMContentLoaded', function () {
    const cartContainer = document.querySelector('.cart-container');
    if (!cartContainer) return;

    // --- DOM Elements ---
    const subtotalEl = document.getElementById('cart-subtotal');
    const totalEl = document.getElementById('cart-total');
    const discountEl = document.getElementById('cart-discount');
    const cartItemsContainer = document.getElementById('cart-items');

    // --- Utility Functions ---
    const formatPrice = (amount) => `${Number(amount).toLocaleString()} LE`;

    const updateTotals = (totals) => {
        if (subtotalEl) subtotalEl.innerText = formatPrice(totals.subtotal);
        if (totalEl) totalEl.innerText = formatPrice(totals.total);
        if (discountEl) discountEl.innerText = `- ${formatPrice(totals.discount)}`;
    };

    const setLoadingState = (row, isLoading) => {
        row.style.opacity = isLoading ? '0.5' : '1';
        row.style.pointerEvents = isLoading ? 'none' : 'auto';
    };

    // --- Event Delegation ---
    cartContainer.addEventListener('click', async (e) => {
        const qtyBtn = e.target.closest('.cart-qty-btn');
        const removeBtn = e.target.closest('.cart-remove-btn');

        if (qtyBtn) {
            handleQuantityUpdate(qtyBtn);
        }

        if (removeBtn) {
            handleRemoveItem(removeBtn);
        }
    });

    // --- Event Handlers ---
    async function handleQuantityUpdate(btn) {
        const variantId = btn.dataset.id;
        const action = btn.dataset.action;
        const row = document.getElementById(`row-${variantId}`);
        const qtyDisplay = document.getElementById(`qty-${variantId}`);
        
        const currentQty = parseInt(qtyDisplay.innerText);
        const newQty = action === 'increase' ? currentQty + 1 : currentQty - 1;

        if (newQty < 1) return;

        // Optimistic UI update
        qtyDisplay.innerText = newQty;
        setLoadingState(row, true);

        const { success, data, error } = await apiService.updateCart(variantId, newQty);

        setLoadingState(row, false);

        if (success) {
            updateTotals(data);
        } else {
            // Revert optimistic update on failure
            qtyDisplay.innerText = currentQty;
            // Optionally, show a toast or alert
            alert(error || 'Failed to update quantity.');
        }
    }

    async function handleRemoveItem(btn) {
        const variantId = btn.dataset.id;
        const row = document.getElementById(`row-${variantId}`);

        if (!confirm('Are you sure you want to remove this item?')) return;

        setLoadingState(row, true);

        const { success, data, error } = await apiService.removeFromCart(variantId);

        if (success) {
            row.remove();
            // The service should return updated totals and cart count
            if (data.totals) {
                updateTotals(data.totals);
            }
            if (data.cartCount !== undefined) {
                updateCartBadge(data.cartCount);
            }
            // Check if cart is now empty
            if (cartItemsContainer && cartItemsContainer.children.length === 0) {
                // You might want to replace the cart content with an "empty cart" message
                window.location.reload(); // Simple solution for now
            }
        } else {
            setLoadingState(row, false);
            alert(error || 'Failed to remove item.');
        }
    }
});
