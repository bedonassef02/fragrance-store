import { updateCartBadge } from './cart-utils';

document.addEventListener('DOMContentLoaded', function () {
    const sizeContainer = document.getElementById('size-container');
    const qtyInput = document.getElementById('quantity-input');
    const qtyMinus = document.getElementById('qty-minus');
    const qtyPlus = document.getElementById('qty-plus');
    const addToBagBtn = document.getElementById('add-to-bag-btn');

    // Variant Logic
    const variantsDataEl = document.getElementById('product-variants-data');
    const variants = variantsDataEl ? JSON.parse(variantsDataEl.dataset.variants || '[]') : [];

    // Color Logic
    const colorBtns = document.querySelectorAll('.color-btn');
    const colorNameDisplay = document.getElementById('selected-color-name');
    let selectedColor = null;

    let selectedSize = null;
    const hiddenSize = document.getElementById('selected-size');
    if (hiddenSize) selectedSize = hiddenSize.value;

    // Initial Selection (First color)
    if (colorBtns.length > 0) {
        selectedColor = colorBtns[0].dataset.color;
        updateUI(selectedColor);
    } else {
        // No colors (e.g. Bags?), ensure sizes are active
        updateUI(null);
    }

    colorBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            selectedColor = btn.dataset.color;
            updateUI(selectedColor);
        });
    });

    function updateUI(color) {
        // Update Swatches
        if (color) {
            colorBtns.forEach(b => {
                if (b.dataset.color === color) {
                    b.classList.remove('border-transparent');
                    b.classList.add('border-moon-gold');
                } else {
                    b.classList.add('border-transparent');
                    b.classList.remove('border-moon-gold');
                }
            });
            if (colorNameDisplay) colorNameDisplay.textContent = color;
        }

        // Update Images (Only if color is selected)
        const thumbs = document.querySelectorAll('.gallery-thumb');
        let firstVisible = null;
        let firstExactMatch = null;

        thumbs.forEach(thumb => {
            const tColor = thumb.dataset.color;
            // If no color selected (null), show all? Or show 'all' tagged.
            // If color selected, show 'all' and matching color.
            if (tColor === 'all' || (color && tColor === color)) {
                thumb.classList.remove('hidden');
                if (!firstVisible) firstVisible = thumb;
                if (color && tColor === color && !firstExactMatch) firstExactMatch = thumb;
            } else {
                if (color) thumb.classList.add('hidden');
            }
        });

        // Update Main Image to first visible if current not visible?
        const mainImage = document.getElementById('main-image');

        if (mainImage && color) {
            // Prioritize exact match (specific color image) over generic 'all' image
            if (firstExactMatch) {
                mainImage.src = firstExactMatch.src;
            } else if (firstVisible) {
                mainImage.src = firstVisible.src;
            }
        }

        // Update Sizes
        const sizeBtns = document.querySelectorAll('.product-size-btn');
        let availableSizes = [];

        if (color) {
            availableSizes = variants.filter(v => v.color === color).map(v => v.size);
        } else {
            // If no color, maybe all sizes available? Or One Size?
            // Use all unique sizes from variants
            availableSizes = [...new Set(variants.map(v => v.size))];
        }

        // Handle Case where Bag has generic One Size but no color logic?
        // If variants empty? Default to enabled.

        if (variants.length > 0) {
            sizeBtns.forEach(btn => {
                const size = btn.dataset.size;
                // Reset Selection
                btn.classList.remove('bg-moon-gold', 'text-moon-dark', 'font-bold', 'shadow-[0_0_10px_rgba(198,168,124,0.3)]');
                btn.classList.add('border-gray-700', 'text-gray-400');

                if (availableSizes.includes(size)) {
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    btn.disabled = false;
                } else {
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    btn.disabled = true;
                }
            });

            // Reset selectedSize
            selectedSize = null;
            // Auto-select if only 1 option (e.g. One Size)
            if (availableSizes.length === 1 && availableSizes[0] === 'One Size') {
                selectedSize = 'One Size';
            }
        }
    }



    if (sizeContainer) {
        sizeContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('product-size-btn')) {
                const btn = e.target;
                if (btn.disabled) return;

                // Deselect all
                document.querySelectorAll('.product-size-btn').forEach(b => {
                    b.classList.remove('bg-moon-gold', 'text-moon-dark', 'font-bold', 'shadow-[0_0_10px_rgba(198,168,124,0.3)]');
                    b.classList.add('border-gray-700', 'text-gray-400');
                });

                btn.classList.remove('border-gray-700', 'text-gray-400');
                btn.classList.add('bg-moon-gold', 'text-moon-dark', 'font-bold', 'shadow-[0_0_10px_rgba(198,168,124,0.3)]');
                selectedSize = btn.dataset.size;
            }
        });
    }

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
            if (qtyInput.max && val >= parseInt(qtyInput.max)) return;
            qtyInput.value = val + 1;
        });
    }

    // Add to Bag Logic
    if (addToBagBtn) {
        addToBagBtn.addEventListener('click', () => {
            if (!selectedSize) {
                alert('Please select a size');
                return;
            }
            if (colorBtns.length > 0 && !selectedColor) {
                alert('Please select a color');
                return;
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
                    color: selectedColor,
                    quantity: parseInt(qtyInput.value)
                })
            })
                .then(response => response.json())
                .then(data => {
                    addToBagBtn.innerText = originalText;
                    addToBagBtn.disabled = false;

                    if (data.success) {
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
    }

    // Gallery Click Logic
    const mainImage = document.getElementById('main-image');
    const thumbs = document.querySelectorAll('.gallery-thumb');
    const zoomModal = document.getElementById('zoom-modal');
    const zoomImg = document.getElementById('zoom-img-full');

    if (mainImage && thumbs.length > 0) {
        thumbs.forEach(thumb => {
            thumb.addEventListener('click', () => {
                const src = thumb.dataset.src || thumb.src;
                mainImage.style.opacity = '0.5';
                setTimeout(() => {
                    mainImage.src = src;
                    mainImage.style.opacity = '1';
                }, 150);

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
