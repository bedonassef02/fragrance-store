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
    let selectedColor = null;

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

            // Smart Logic: Parse Variants
            const variantsData = JSON.parse(btn.dataset.variants || '[]');
            console.log('QuickAdd Variants:', variantsData);

            const uniqueColors = [...new Set(variantsData.map(v => v.color).filter(Boolean))];
            const uniqueSizes = [...new Set(variantsData.map(v => v.size).filter(s => s && s !== 'One Size'))];
            console.log('Unique Colors:', uniqueColors, 'Unique Sizes:', uniqueSizes);

            // If No Colors and No Sizes (meaning either empty, or 'One Size' with no color)
            // Directly Add to Cart
            if (uniqueColors.length === 0 && uniqueSizes.length === 0) {
                addToCartSimple(selectedProductId);
                return;
            }

            // Setup Modal for Options
            modalTitle.textContent = name;
            modalPrice.textContent = price;

            // Show Options Container
            if (sizeContainer) {
                sizeContainer.classList.remove('hidden');
            }

            // Clear previous content
            sizeButtonsContainer.innerHTML = '';

            // Reset Selectors
            selectedSize = null;
            selectedColor = null;

            // Render Colors (if any)
            if (uniqueColors.length > 0) {
                const colorLabel = document.createElement('p');
                colorLabel.className = 'text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide';
                colorLabel.innerText = 'Select Color';
                sizeButtonsContainer.appendChild(colorLabel);

                const colorsDiv = document.createElement('div');
                colorsDiv.className = 'flex gap-3 mb-6';

                // Stock Logic & Default Selection
                // Filter colors that have at least one variant with stock > 0
                // User said: "allow qty per color and size if not implemented". Variants data has 'qty'.
                // If a color has 0 stock across all sizes, maybe we shouldn't show it? 
                // Or user said "allow out of stock badge". So we show it but disable it.

                // Default Selection: Find first IN STOCK color
                if (!selectedColor) {
                    const inStockColor = uniqueColors.find(c => {
                        return variantsData.some(v => v.color === c && v.qty > 0);
                    });
                    // If all out of stock, just pick first
                    selectedColor = inStockColor || uniqueColors[0];
                }

                // Single Option Auto-Add Logic
                // If only 1 Color exists AND it has only 1 Size (or no size) AND it is in stock -> Add Directly.
                // But wait, user might want to see the modal confirmation.
                // The user said: "if there's a product that have only one color and only one size ... dont show the option".
                // Let's implement this check here.

                const validColors = uniqueColors.filter(c => variantsData.some(v => v.color === c && v.qty > 0));

                if (validColors.length === 1) {
                    const onlyColor = validColors[0];
                    const onlyColorVariants = variantsData.filter(v => v.color === onlyColor && v.qty > 0);
                    const onlyColorSizes = [...new Set(onlyColorVariants.map(v => v.size).filter(s => s && s !== 'One Size'))];

                    if (onlyColorSizes.length <= 1) { // 0 or 1 size
                        // Just Add It
                        // We need to determine the exact variant to add? 
                        // Actually addToCartSimple handles product_id. But if we have specific variant data (size/color), we should use the complex add.

                        // If no size (One Size)
                        selectedColor = onlyColor;
                        selectedSize = onlyColorSizes[0] || null; // 'One Size' treated as null in request usually? Or explicitly 'One Size'?

                        // We need the ID? No, endpoint takes product_id + color + size.
                        // We can reuse the confirmBtn logic but headless.

                        // Trigger Add immediately
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                        fetch('/cart/add', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                            body: JSON.stringify({
                                product_id: selectedProductId,
                                size: selectedSize,
                                color: selectedColor,
                                quantity: 1
                            })
                        })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) { showToast(); updateCartBadge(data.cartCount); }
                                else { alert(data.error); }
                            });
                        return; // Stop modal open
                    }
                }

                uniqueColors.forEach(color => {
                    const variant = variantsData.find(v => v.color === color);
                    const hex = variant ? variant.color_hex : '#000';

                    // Check if entire color is out of stock (all sizes 0)
                    const isOutOfStock = variantsData.filter(v => v.color === color).every(v => v.qty === 0);

                    const cBtn = document.createElement('button');
                    cBtn.className = 'w-10 h-10 rounded-full border-2 border-transparent transition-all relative';
                    cBtn.style.backgroundColor = hex;
                    cBtn.title = color + (isOutOfStock ? ' (Sold Out)' : '');
                    cBtn.dataset.color = color;

                    if (isOutOfStock) {
                        cBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        cBtn.disabled = true;

                        const slash = document.createElement('div');
                        slash.className = 'absolute inset-0 bg-red-500/50 rotate-45 transform scale-x-110 h-0.5 top-1/2 -mt-px';
                        cBtn.appendChild(slash);
                    } else {
                        cBtn.addEventListener('click', () => {
                            colorsDiv.querySelectorAll('button').forEach(b => {
                                b.classList.remove('border-moon-gold', 'scale-110');
                                b.classList.add('border-transparent');
                            });
                            cBtn.classList.remove('border-transparent');
                            cBtn.classList.add('border-moon-gold', 'scale-110');

                            selectedColor = color;
                            selectedSize = null;

                            // Auto-select first IN STOCK size
                            const newVariants = variantsData.filter(v => v.color === color);
                            const availableSizes = [...new Set(newVariants.filter(v => v.qty > 0).map(v => v.size).filter(s => s && s !== 'One Size'))];

                            if (availableSizes.length > 0) {
                                selectedSize = availableSizes[0];
                            }

                            renderSizes(newVariants, false, variantsData);
                        });
                    }

                    // Highlight if selected
                    if (color === selectedColor) {
                        cBtn.classList.add('border-moon-gold', 'scale-110');
                    } else if (!isOutOfStock) {
                        cBtn.classList.add('hover:border-moon-gold', 'hover:scale-105');
                    }

                    colorsDiv.appendChild(cBtn);
                });
                sizeButtonsContainer.appendChild(colorsDiv);
            }

            // Auto-select size if not set (for initial load)
            if (!selectedSize && uniqueSizes.length > 0) {
                const currentVariants = selectedColor ? variantsData.filter(v => v.color === selectedColor) : variantsData;
                const currentSizes = [...new Set(currentVariants.filter(v => v.qty > 0).map(v => v.size).filter(s => s && s !== 'One Size'))];
                if (currentSizes.length > 0) selectedSize = currentSizes[0];
            }

            // Render Sizes
            const filteredVariants = selectedColor ? variantsData.filter(v => v.color === selectedColor) : variantsData;
            renderSizes(filteredVariants, uniqueColors.length > 0, variantsData);

            // Open Modal
            modal.classList.remove('hidden');
            showBackdrop();
            setTimeout(() => {
                modal.classList.remove('opacity-0', 'scale-95');
                modal.classList.add('opacity-100', 'scale-100');
            }, 10);
        });
    });

    function renderSizes(currentVariants, waitForColor = false, allVariants = []) {
        // Use allVariants to get global sizes list if provided, otherwise fallback to current
        const sourceVariants = allVariants.length > 0 ? allVariants : currentVariants;
        const allSizes = [...new Set(sourceVariants.map(v => v.size).filter(s => s && s !== 'One Size'))];

        // If sorting needed, we could sort S < M < L here. For now, rely on insertion order.

        let sizeDiv = document.getElementById('modal-size-wrapper');
        if (!sizeDiv) {
            sizeDiv = document.createElement('div');
            sizeDiv.id = 'modal-size-wrapper';
            sizeDiv.className = 'flex flex-wrap gap-2';
            sizeButtonsContainer.appendChild(sizeDiv);
        } else {
            sizeDiv.innerHTML = '';
        }

        if (waitForColor && !selectedColor) {
            const msg = document.createElement('span');
            msg.className = 'text-gray-500 text-sm';
            msg.innerText = 'Select a color to see sizes';
            sizeDiv.appendChild(msg);
            return;
        }

        if (allSizes.length === 0) {
            return;
        }

        allSizes.forEach(size => {
            // Check availability for SELECTED color (currentVariants)
            const variant = currentVariants.find(v => v.size === size);

            // Invalid: Variant doesn't exist for this color
            const isInvalidCombination = !variant;

            // Out of Stock: Variant exists but qty is 0
            const isOutOfStock = variant && variant.qty === 0;

            // Determine State
            const isDisabled = isInvalidCombination || isOutOfStock;

            const sBtn = document.createElement('button');
            sBtn.className = 'size-btn w-12 h-12 border border-gray-600 text-gray-400 font-bold hover:border-moon-gold hover:text-white transition-all hover:scale-110 rounded-sm relative';
            sBtn.innerText = size;

            if (isDisabled) {
                sBtn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-800');
                sBtn.disabled = true;

                // Visual Cue
                if (isOutOfStock) {
                    // Slash for Sold Out
                    const slash = document.createElement('div');
                    slash.className = 'absolute inset-0 bg-gray-500/50 rotate-45 transform scale-x-110 h-0.5 top-1/2 -mt-px';
                    sBtn.appendChild(slash);
                    sBtn.title = 'Sold Out';
                } else {
                    // Just Disabled (Not available in this color)
                    sBtn.style.opacity = '0.3';
                    sBtn.title = 'Not available in this color';
                }
            } else {
                sBtn.addEventListener('click', () => {
                    sizeDiv.querySelectorAll('.size-btn').forEach(b => {
                        b.classList.remove('bg-moon-gold', 'text-black', 'border-moon-gold');
                        b.classList.add('text-gray-400', 'border-gray-600');
                    });
                    sBtn.classList.remove('text-gray-400', 'border-gray-600');
                    sBtn.classList.add('bg-moon-gold', 'text-black', 'border-moon-gold');
                    selectedSize = size;
                });

                // Initial Highlight if set
                if (size === selectedSize) {
                    sBtn.classList.remove('text-gray-400', 'border-gray-600');
                    sBtn.classList.add('bg-moon-gold', 'text-black', 'border-moon-gold');
                }
            }

            sizeDiv.appendChild(sBtn);
        });
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', () => {

            const hasColors = sizeButtonsContainer.querySelectorAll('button[title]').length > 0;
            const hasSizes = sizeButtonsContainer.querySelectorAll('.size-btn').length > 0;

            if (hasColors && !selectedColor) {
                alert('Please select a color');
                return;
            }
            if (hasSizes && !selectedSize) {
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
                    color: selectedColor,
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
    }

    function addToCartSimple(productId) {
        // Fetch add ...
        // Re-use confirm logic but without modal?
        // Or trigger modal with "Adding..." state immediately?
        // Better: trigger Fetch directly.

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('/cart/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) { showToast(); updateCartBadge(data.cartCount); }
                else { alert(data.error); }
            });
    }
});
