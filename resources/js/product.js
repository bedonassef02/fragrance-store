import { updateCartBadge } from './cart-utils';

document.addEventListener('DOMContentLoaded', function () {
    const sizeBtns = document.querySelectorAll('.product-size-btn');
    const qtyInput = document.getElementById('quantity-input');
    const qtyMinus = document.getElementById('qty-minus');
    const qtyPlus = document.getElementById('qty-plus');
    const addToBagBtn = document.getElementById('add-to-bag-btn');
    // If no size buttons, default to 'One Size'
    let selectedSize = sizeBtns.length === 0 ? 'One Size' : null;

    if (!addToBagBtn) return;

    // Size Selection
    sizeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            sizeBtns.forEach(b => {
                b.classList.remove('bg-moon-gold', 'text-moon-dark', 'font-bold', 'shadow-[0_0_10px_rgba(198,168,124,0.3)]');
                b.classList.add('border-gray-700', 'text-gray-400');
            });

            btn.classList.remove('border-gray-700', 'text-gray-400');
            btn.classList.add('bg-moon-gold', 'text-moon-dark', 'font-bold', 'shadow-[0_0_10px_rgba(198,168,124,0.3)]');

            selectedSize = btn.dataset.size;
        });
    });

    // Quantity Logic
    if (qtyMinus) {
        qtyMinus.addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            if (val > 1) qtyInput.value = val - 1;
        });
    }
    if (qtyPlus) {
        qtyPlus.addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            qtyInput.value = val + 1;
        });
    }

    // Add to Bag Logic
    addToBagBtn.addEventListener('click', () => {
        if (!selectedSize) {
            alert('Please select a size');
            return;
        }

        const originalText = addToBagBtn.innerText;
        addToBagBtn.innerText = 'Adding...';
        addToBagBtn.disabled = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        // We need product ID. 
        // Best way: data attribute on the button. 
        // I will need to update the blade view to add data-id property to the button, 
        // or parse it from URL (less reliable) or a hidden input.
        // I will assume I will add `data-id` to the button in the blade file.
        const productId = addToBagBtn.dataset.id;

        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                product_id: productId,
                size: selectedSize,
                quantity: parseInt(qtyInput.value)
            })
        })
            .then(response => response.json())
            .then(data => {
                addToBagBtn.innerText = originalText;
                addToBagBtn.disabled = false;

                if (data.success) {
                    alert('Added to Bag!');
                    updateCartBadge(data.cartCount);
                } else {
                    alert(data.error || 'Something went wrong');
                }
            })
            .catch(e => {
                console.error(e);
                addToBagBtn.innerText = originalText;
                addToBagBtn.disabled = false;
            });
    });
});
