document.addEventListener('DOMContentLoaded', function () {
    const qtyBtns = document.querySelectorAll('.cart-qty-btn');
    const removeBtns = document.querySelectorAll('.cart-remove-btn');
    const subtotalEl = document.getElementById('cart-subtotal');
    const totalEl = document.getElementById('cart-total');

    if (qtyBtns.length === 0 && removeBtns.length === 0) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    qtyBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.dataset.key;
            const action = btn.dataset.action;
            const qtyDisplay = document.getElementById('qty-' + key);
            let currentQty = parseInt(qtyDisplay.innerText);
            let newQty = action === 'increase' ? currentQty + 1 : currentQty - 1;

            if (newQty < 1) return;

            // Optimistic UI update
            qtyDisplay.innerText = newQty;

            fetch('/cart/update', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    id: key,
                    quantity: newQty
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (subtotalEl) subtotalEl.innerText = data.subtotal + ' LE';
                        if (totalEl) totalEl.innerText = data.total + ' LE';
                    }
                });
        });
    });

    removeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.dataset.key;
            if (!confirm('Remove this item?')) return;

            fetch('/cart/remove', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    id: key
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.getElementById('row-' + key);
                        if (row) row.remove();
                        // Reload to update totals cleanly for now
                        window.location.reload();
                    }
                });
        });
    });
});
