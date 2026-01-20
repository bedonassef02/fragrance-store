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

    // Initial Selection (First IN-STOCK color)
    if (colorBtns.length > 0) {
        // Find first color that has qty > 0
        let defaultColor = colorBtns[0].dataset.color;

        // Find first color in variants with qty > 0
        // We need reference to unique colors order? Or just any in-stock?
        // Let's iterate colorBtns to respect display order.
        for (let btn of colorBtns) {
            const c = btn.dataset.color;
            const hasStock = variants.some(v => v.color === c && v.qty > 0);
            if (hasStock) {
                defaultColor = c;
                break;
            }
        }

        selectedColor = defaultColor;
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

                // Check Validity and Stock
                // 1. Check if size exists for this color (Validity)
                const variantForColor = variants.find(v => v.size === size && (color ? v.color === color : true));
                const isValid = !!variantForColor;

                // 2. Check Stock
                const isOutOfStock = isValid && variantForColor.qty === 0;

                if (isValid && !isOutOfStock) {
                    btn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-800');
                    btn.disabled = false;
                    btn.title = '';
                } else {
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    btn.disabled = true;

                    if (isOutOfStock) {
                        btn.title = 'Sold Out';
                        btn.classList.add('bg-gray-800'); // Darker background for sold out
                    } else {
                        btn.title = 'Not available in this color';
                        btn.classList.remove('bg-gray-800');
                    }
                }
            });

            // Reset selectedSize
            selectedSize = null;

            // Auto-select first IN-STOCK Size for this color
            // Get all size buttons that are NOT disabled
            const enabledBtns = Array.from(sizeBtns).filter(btn => !btn.disabled);

            if (enabledBtns.length > 0) {
                const firstBtn = enabledBtns[0];
                selectedSize = firstBtn.dataset.size;

                // Highlight it
                firstBtn.classList.remove('border-gray-700', 'text-gray-400');
                firstBtn.classList.add('bg-moon-gold', 'text-moon-dark', 'font-bold', 'shadow-[0_0_10px_rgba(198,168,124,0.3)]');
            } else if (availableSizes.length === 1 && availableSizes[0] === 'One Size') {
                // Even if disabled (SOLD OUT), if it's One Size we might want to track it?
                // But validation prevents adding.
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
            // Check if size selection is mandatory
            // If availableSizes is empty or only "One Size", and no other options, maybe we allow it?
            // But Wait: 'availableSizes' is local to updateUI.
            // We need to know if size selection is possible.

            // Simplest Helper: Check if size buttons specific to this color exist and are enabled.
            const enabledSizeBtns = document.querySelectorAll('.product-size-btn:not([disabled])');
            const hasSizeOptions = enabledSizeBtns.length > 0;
            const needsSize = hasSizeOptions && !selectedSize;

            if (needsSize) {
                // Special check: If only 1 option is 'One Size', maybe it's auto-selected?
                // Logic in updateUI auto-selects 'One Size'. So selectedSize should be set.
                // If it's NOT set, it means user hasn't clicked it or auto-select failed.
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
