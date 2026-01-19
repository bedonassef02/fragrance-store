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
            // Check buttons
            if (sizeBtns.length === 0) {
                selectedSize = 'One Size';
            } else {
                alert('Please select a size');
                return;
            }
        }

        const originalText = addToBagBtn.innerText;
        addToBagBtn.innerText = 'Adding...';
        addToBagBtn.disabled = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
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
                    // Show Toast or Alert
                    // We can reuse the Toast from quick-add if available globally?
                    // quick-add.js handles its own toast.
                    // Simple alert for now or implement toast.
                    const toast = document.getElementById('toast');
                    if (toast) {
                        toast.classList.remove('translate-y-20', 'opacity-0');
                        setTimeout(() => {
                            toast.classList.add('translate-y-20', 'opacity-0');
                        }, 3000);
                    } else {
                        alert('Added to Bag!');
                    }
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

    // Gallery Logic
    const mainImage = document.getElementById('main-image');
    const thumbs = document.querySelectorAll('.gallery-thumb');
    const zoomModal = document.getElementById('zoom-modal');
    const zoomImg = document.getElementById('zoom-img-full');

    if (mainImage && thumbs.length > 0) {
        thumbs.forEach(thumb => {
            thumb.addEventListener('click', () => {
                const src = thumb.dataset.src || thumb.src;
                // Fade effect?
                mainImage.style.opacity = '0.5';
                setTimeout(() => {
                    mainImage.src = src;
                    mainImage.style.opacity = '1';
                }, 150);

                // Active state
                thumbs.forEach(t => t.classList.remove('border-moon-gold'));
                thumb.classList.add('border-moon-gold');
            });
        });
    }

    // Zoom Logic
    if (mainImage && zoomModal && zoomImg) {
        mainImage.addEventListener('click', () => {
            zoomImg.src = mainImage.src;
            zoomModal.classList.remove('hidden');
            zoomModal.classList.add('flex');
            // Animation
            setTimeout(() => {
                zoomImg.classList.remove('scale-90', 'opacity-0');
                zoomImg.classList.add('scale-100', 'opacity-100');
            }, 10);
        });

        zoomModal.addEventListener('click', () => {
            zoomImg.classList.remove('scale-100', 'opacity-100');
            zoomImg.classList.add('scale-90', 'opacity-0');
            setTimeout(() => {
                zoomModal.classList.remove('flex');
                zoomModal.classList.add('hidden');
            }, 300);
        });
    }
});
