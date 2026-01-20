import apiService from './apiService';
import { updateCartBadge } from './cart-utils';
import { showToast } from './ui-helpers';

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
        if (discountEl) {
            discountEl.innerText = `- ${formatPrice(totals.discount)}`;
            // Show/Hide discount row based on value
            discountEl.parentElement.style.display = totals.discount > 0 ? 'flex' : 'none';
        }

        // Update Shipping
        const shippingEl = document.getElementById('cart-shipping');
        if (shippingEl) {
            shippingEl.innerText = totals.shipping > 0 ? formatPrice(totals.shipping) : 'Free';
        }
    };

    const setLoadingState = (row, isLoading) => {
        if (row) {
            row.style.opacity = isLoading ? '0.5' : '1';
            row.style.pointerEvents = isLoading ? 'none' : 'auto';
        }
    };

    // --- Event Delegation ---
    cartContainer.addEventListener('click', async (e) => {
        const qtyBtn = e.target.closest('.cart-qty-btn');
        const removeBtn = e.target.closest('.cart-remove-btn');
        const removeCouponBtn = e.target.closest('#remove-coupon-btn');

        if (qtyBtn) handleQuantityUpdate(qtyBtn);
        if (removeBtn) handleRemoveItem(removeBtn);
        if (removeCouponBtn) handleRemoveCoupon(removeCouponBtn);
    });

    const couponForm = document.getElementById('coupon-form');
    if (couponForm) {
        couponForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const input = couponForm.querySelector('input[name="code"]');
            const btn = couponForm.querySelector('button');
            const code = input.value;

            if (!code) return;

            btn.disabled = true;
            btn.innerText = 'Applying...';

            const { success, data, error } = await apiService.applyCoupon(code);

            btn.disabled = false;
            btn.innerText = 'Apply';

            if (success) {
                updateTotals(data.totals);
                // Reload to show correct coupon UI state (simplest for now)
                window.location.reload();
            } else {
                const msgEl = document.getElementById('coupon-message');
                if (msgEl) {
                    msgEl.innerText = error || 'Invalid coupon code';
                    msgEl.className = 'mt-2 text-xs font-medium text-red-500 fade-in';
                    msgEl.classList.remove('hidden');
                } else {
                    showToast(error || 'Invalid coupon code', true);
                }
            }
        });

        // Clear error on input
        const input = couponForm.querySelector('input[name="code"]');
        input.addEventListener('input', () => {
            const msgEl = document.getElementById('coupon-message');
            if (msgEl) msgEl.classList.add('hidden');
        });
    }

    async function handleRemoveCoupon(btn) {
        btn.disabled = true;
        btn.innerText = '...';

        const { success, data, error } = await apiService.removeCoupon();

        if (success) {
            updateTotals(data.totals);
            window.location.reload();
        } else {
            showToast(error || 'Failed to remove coupon', true);
            btn.disabled = false;
            btn.innerText = 'Remove';
        }
    }

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
            updateTotals(data.totals);
            updateCartBadge(data.cartCount);
        } else {
            qtyDisplay.innerText = currentQty;
            showToast(error || 'Failed to update quantity', true);
        }
    }

    async function handleRemoveItem(btn) {
        const variantId = btn.dataset.id;
        const row = document.getElementById(`row-${variantId}`);

        setLoadingState(row, true);

        const { success, data, error } = await apiService.removeFromCart(variantId);

        if (success) {
            row.remove();
            if (data.totals) updateTotals(data.totals);
            if (data.cartCount !== undefined) updateCartBadge(data.cartCount);

            const cartItemsContainer = document.getElementById('cart-items');
            if (cartItemsContainer && cartItemsContainer.children.length === 0) {
                window.location.reload();
            }
        } else {
            setLoadingState(row, false);
            showToast(error || 'Failed to remove item', true);
        }
    }
});
