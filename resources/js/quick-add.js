import { updateCartBadge } from './cart-utils';

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('quick-add-modal');
    if (!modal) return;

    const closeModalBtn = document.getElementById('close-modal');
    const modalTitle = document.getElementById('modal-product-title');
    const modalPrice = document.getElementById('modal-product-price');
    const confirmBtn = document.getElementById('confirm-add-to-bag');
    const toast = document.getElementById('toast');
    const sizeBtns = modal.querySelectorAll('.size-btn');
    const backdrop = document.getElementById('backdrop');

    // The buttons trigger logic needs to be delegated or added to existing buttons
    // Since buttons might be dynamically rendered or exist on multiple pages, 
    // we should use event delegation or query them.
    // However, looking for '.quick-add-btn' is safe.
    const addToBagBtns = document.querySelectorAll('.quick-add-btn');

    let selectedProductId = null;
    let selectedSize = null;

    function openModal(title, price) {
        modalTitle.textContent = title;
        modalPrice.textContent = price;
        modal.classList.remove('hidden');
        showBackdrop();
        // Animation frame
        setTimeout(() => {
            modal.classList.remove('opacity-0', 'scale-95');
            modal.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closeModal() {
        modal.classList.remove('opacity-100', 'scale-100');
        modal.classList.add('opacity-0', 'scale-95');
        hideBackdrop();
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function showBackdrop() {
        if (backdrop) {
            backdrop.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
            }, 10);
        }
    }

    function hideBackdrop() {
        if (backdrop) {
            backdrop.classList.add('opacity-0');
            setTimeout(() => {
                backdrop.classList.add('hidden');
            }, 300);
        }
    }

    function showToast() {
        if (!toast) return;
        toast.classList.remove('translate-y-20', 'opacity-0');
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3000);
    }

    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);

    // Check if backdrop is clicked (shared with filters, so be careful not to override filter's listener if separate)
    // Actually, adding multiple listeners to backdrop is fine.
    if (backdrop) backdrop.addEventListener('click', closeModal);

    const sizeContainer = document.getElementById('modal-size-container');
    const sizeButtonsContainer = document.getElementById('modal-size-buttons');

    addToBagBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            selectedProductId = btn.dataset.id;
            const name = btn.dataset.name || 'Product';
            const price = btn.dataset.price || '';
            const sizesStr = btn.dataset.sizes || '';

            // Set content
            modalTitle.textContent = name;
            modalPrice.textContent = price;

            // Reset Sizes
            sizeButtonsContainer.innerHTML = '';
            selectedSize = null;

            if (sizesStr) {
                sizeContainer.classList.remove('hidden');
                const sizes = sizesStr.split(',');

                sizes.forEach(size => {
                    const sBtn = document.createElement('button');
                    sBtn.className = 'size-btn w-12 h-12 border border-gray-600 text-gray-400 font-bold hover:border-moon-gold hover:text-white transition-all hover:scale-110 rounded-sm';
                    sBtn.innerText = size;

                    sBtn.addEventListener('click', () => {
                        // Deactivate all
                        const allBtns = sizeButtonsContainer.querySelectorAll('.size-btn');
                        allBtns.forEach(b => {
                            b.classList.remove('bg-moon-gold', 'text-black', 'border-moon-gold');
                            b.classList.add('text-gray-400', 'border-gray-600');
                        });
                        // Activate clicked
                        sBtn.classList.remove('text-gray-400', 'border-gray-600');
                        sBtn.classList.add('bg-moon-gold', 'text-black', 'border-moon-gold');
                        selectedSize = size;
                    });

                    sizeButtonsContainer.appendChild(sBtn);
                });
            } else {
                // No sizes (e.g. Bags)
                sizeContainer.classList.add('hidden');
                selectedSize = 'One Size';
            }

            // Open Modal
            modal.classList.remove('hidden');
            showBackdrop();
            setTimeout(() => {
                modal.classList.remove('opacity-0', 'scale-95');
                modal.classList.add('opacity-100', 'scale-100');
            }, 10);
        });
    });

    // Removed static sizeBtns listener as they are now dynamic

    if (confirmBtn) confirmBtn.addEventListener('click', () => {
        if (!selectedSize) {
            alert('Please select a size');
            return;
        }

        const originalText = confirmBtn.innerText;
        confirmBtn.innerText = 'Adding...';
        confirmBtn.disabled = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                product_id: selectedProductId,
                size: selectedSize,
                quantity: 1
            })
        })
            .then(response => response.json())
            .then(data => {
                confirmBtn.innerText = originalText;
                confirmBtn.disabled = false;

                if (data.success) {
                    closeModal();
                    showToast();
                    updateCartBadge(data.cartCount);
                } else {
                    alert(data.error || 'Something went wrong');
                }
            })
            .catch(err => {
                console.error(err);
                confirmBtn.innerText = originalText;
                confirmBtn.disabled = false;
            });
    });
});
