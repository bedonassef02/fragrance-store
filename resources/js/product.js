import { updateCartBadge } from './cart-utils';
import apiService from './apiService';
import { showToast, setLoading } from './ui-helpers';

document.addEventListener('DOMContentLoaded', function () {
    const variantsDataEl = document.getElementById('product-variants-data');

    // Only run on product pages
    if (!variantsDataEl) return;

    // --- DOM Elements ---
    const capacityContainer = document.getElementById('capacity-container');
    const addToBagBtn = document.getElementById('add-to-bag-btn');
    const mainImage = document.getElementById('main-image');
    const galleryThumbs = document.querySelectorAll('.gallery-thumb');
    const qtyInput = document.getElementById('quantity-input');
    const priceEl = document.getElementById('product-price');
    const selectedCapacityInput = document.getElementById('selected-capacity');

    // Quantity Controls
    const qtyMinus = document.getElementById('qty-minus');
    const qtyPlus = document.getElementById('qty-plus');

    // Zoom Controls
    const zoomModal = document.getElementById('zoom-modal');
    const zoomImg = document.getElementById('zoom-img-full');

    // Review Controls
    const toggleReviewsBtn = document.getElementById('toggle-reviews-btn');
    const reviewsContainer = document.getElementById('reviews-container');
    const loadMoreContainer = document.getElementById('load-more-container');
    const loadMoreBtn = document.getElementById('load-more-reviews-btn');

    // --- State ---
    let state = {
        variants: JSON.parse(variantsDataEl.dataset.variants || '[]'),
        selectedCapacity: null,
        selectedVariant: null,
        reviewsPage: 1,
        reviewsLoaded: false,
    };

    // --- UI Functions ---
    const ui = {
        updateCapacityButtons() {
            if (!capacityContainer) return;
            const capBtns = capacityContainer.querySelectorAll('.product-capacity-btn');

            capBtns.forEach(btn => {
                const capacity = btn.dataset.capacity;
                const isSelected = capacity === state.selectedCapacity;

                if (isSelected) {
                    btn.classList.add('selected');
                } else {
                    btn.classList.remove('selected');
                }
            });
        },

        updatePrice() {
            if (!priceEl || !state.selectedVariant) return;
            priceEl.textContent = state.selectedVariant.price + ' LE';
        },

        updateAddToBagButton() {
            if (!addToBagBtn) return;

            if (!state.selectedVariant) {
                if (state.variants.length > 0) {
                    addToBagBtn.disabled = true;
                    addToBagBtn.innerText = 'Select Capacity';
                    addToBagBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    addToBagBtn.disabled = false;
                    addToBagBtn.innerText = 'Add to Bag';
                    addToBagBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
                return;
            }

            addToBagBtn.dataset.id = state.selectedVariant.id;

            if (state.selectedVariant.qty === 0) {
                addToBagBtn.disabled = true;
                addToBagBtn.innerText = 'Out of Stock';
                addToBagBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                addToBagBtn.disabled = false;
                addToBagBtn.innerText = 'Add to Bag';
                addToBagBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        },

        updateAll() {
            this.updateCapacityButtons();

            if (state.selectedCapacity) {
                state.selectedVariant = state.variants.find(v => v.capacity === state.selectedCapacity);
            }

            this.updatePrice();
            this.updateAddToBagButton();

            if (selectedCapacityInput) {
                selectedCapacityInput.value = state.selectedCapacity || '';
            }
        }
    };

    // --- Review Logic ---
    async function loadReviews() {
        if (!toggleReviewsBtn) return;
        const slug = toggleReviewsBtn.dataset.slug;
        const btnToLoading = state.reviewsPage === 1 ? toggleReviewsBtn : loadMoreBtn;

        try {
            setLoading(btnToLoading, true, 'Loading...', state.reviewsPage === 1 ? 'Show Customer Reviews' : 'Load More');

            const response = await fetch(`/product/${slug}/reviews?page=${state.reviewsPage}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();

            if (data.data.length > 0) {
                renderReviews(data.data);

                if (state.reviewsPage === 1) {
                    reviewsContainer.classList.remove('hidden');
                    // Small delay to allow display:block to apply before opacity transition
                    requestAnimationFrame(() => {
                        reviewsContainer.classList.remove('opacity-0');
                    });
                    toggleReviewsBtn.parentElement.classList.add('hidden'); // Hide the main toggle button
                }

                if (data.next_page_url) {
                    loadMoreContainer.classList.remove('hidden');
                    state.reviewsPage++;
                } else {
                    loadMoreContainer.classList.add('hidden');
                }
            } else {
                if (state.reviewsPage === 1) {
                    // No reviews at all
                    showToast('No reviews yet.', true);
                } else {
                    loadMoreContainer.classList.add('hidden');
                }
            }
        } catch (error) {
            console.error('Error loading reviews:', error);
            showToast('Failed to load reviews.', true);
        } finally {
            setLoading(btnToLoading, false);
        }
    }

    function renderReviews(reviews) {
        reviews.forEach(review => {
            const card = document.createElement('div');
            card.className = 'bg-white/5 border border-white/10 p-6 rounded-sm fade-in';

            const stars = Array(5).fill(0).map((_, i) => i < review.rating ? '★' : '☆').join('');
            const userName = review.user ? review.user.name : 'Verified Buyer';
            // Simple date formatting
            const date = new Date(review.created_at).toLocaleDateString();

            let imageHtml = '';
            if (review.image_path) {
                imageHtml = `<img src="/storage/${review.image_path}" class="w-20 h-20 object-cover rounded-sm border border-gray-700 cursor-zoom-in mt-4 hover:opacity-80 transition-opacity" onclick="document.getElementById('zoom-img-full').src=this.src; document.getElementById('zoom-modal').classList.remove('hidden', 'opacity-0'); document.getElementById('zoom-modal').classList.add('flex');">`;
            }

            card.innerHTML = `
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="flex text-moon-gold text-sm mb-1 tracking-widest">${stars}</div>
                        <span class="text-white text-sm font-bold">${userName}</span>
                    </div>
                    <span class="text-xs text-gray-500">${date}</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">${review.comment}</p>
                ${imageHtml}
            `;
            reviewsContainer.appendChild(card);
        });
    }

    if (toggleReviewsBtn) {
        toggleReviewsBtn.addEventListener('click', loadReviews);
    }

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', loadReviews);
    }


    // --- Event Listeners (Existing) ---
    capacityContainer?.addEventListener('click', (e) => {
        const btn = e.target.closest('.product-capacity-btn');
        if (btn) {
            state.selectedCapacity = btn.dataset.capacity;
            ui.updateAll();
        }
    });

    addToBagBtn?.addEventListener('click', async () => {
        if (state.variants.length > 0 && !state.selectedVariant) {
            showToast('Please select a capacity.', true);
            return;
        }
        if (state.selectedVariant && state.selectedVariant.qty === 0) {
            showToast('This item is out of stock.', true);
            return;
        }
        const idToAdd = state.selectedVariant ? state.selectedVariant.id : addToBagBtn.dataset.id;
        if (!idToAdd) return;

        setLoading(addToBagBtn, true, 'Adding...', 'Add to Bag');
        const quantity = parseInt(qtyInput.value) || 1;

        try {
            const { success, data, error } = await apiService.addToCart(idToAdd, quantity);
            if (success) {
                showToast('Item added to bag!');
                updateCartBadge(data.cartCount);
            } else {
                showToast(error || 'Failed to add item', true);
            }
        } catch (err) {
            showToast('An unexpected error occurred.', true);
        } finally {
            setLoading(addToBagBtn, false);
        }
    });

    qtyMinus?.addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (val > 1) qtyInput.value = val - 1;
    });

    qtyPlus?.addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (qtyInput.max && val >= parseInt(qtyInput.max)) return;
        qtyInput.value = val + 1;
    });

    galleryThumbs.forEach(thumb => {
        thumb.addEventListener('click', () => {
            const src = thumb.getAttribute('src');
            mainImage.style.opacity = '0.5';
            setTimeout(() => {
                mainImage.src = src;
                mainImage.style.opacity = '1';
            }, 150);
            galleryThumbs.forEach(t => t.classList.remove('border-moon-gold'));
            thumb.classList.add('border-moon-gold');
        });
    });

    // Zoom Logic
    mainImage?.addEventListener('click', () => {
        if (zoomModal && zoomImg) {
            zoomImg.src = mainImage.src;
            zoomModal.classList.remove('hidden');
            zoomModal.classList.add('flex');
            requestAnimationFrame(() => {
                zoomImg.classList.remove('scale-90', 'opacity-0');
            });
        }
    });

    zoomModal?.addEventListener('click', (e) => {
        if (e.target.id === 'close-zoom' || e.target === zoomModal) {
            if (zoomImg) {
                zoomImg.classList.add('scale-90', 'opacity-0');
            }
            setTimeout(() => {
                zoomModal.classList.add('hidden');
                zoomModal.classList.remove('flex');
            }, 300);
        }
    });

    // --- Initialization ---
    function initialize() {
        if (state.variants.length > 0) {
            state.selectedCapacity = state.variants[0].capacity;
        }
        ui.updateAll();
    }

    initialize();
});
