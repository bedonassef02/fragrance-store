document.addEventListener('DOMContentLoaded', function () {
    const wishlistButtons = document.querySelectorAll('.wishlist-toggle');

    // highlight active hearts based on cookie
    const getWishlist = () => {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; moon_wishlist=`);
        if (parts.length === 2) {
            try {
                // Now that cookie is unencrypted, it should be a JSON string or URL-encoded JSON
                let cookieVal = parts.pop().split(';').shift();
                return JSON.parse(decodeURIComponent(cookieVal));
            } catch (e) {
                console.error("Error parsing wishlist cookie", e);
                return [];
            }
        }
        return [];
    };

    const updateWishlistBadge = (count) => {
        const badge = document.getElementById('wishlist-count');
        if (!badge) return;

        badge.textContent = count;
        if (count > 0) {
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    };

    const wishlist = getWishlist();
    updateWishlistBadge(wishlist.length);

    wishlistButtons.forEach(btn => {
        if (wishlist.includes(parseInt(btn.dataset.id))) {
            btn.classList.add('active');
            const svg = btn.querySelector('svg');
            if (svg) svg.classList.add('fill-moon-gold', 'text-moon-gold');
        }
    });

    wishlistButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation(); // prevent triggering card link

            const productId = this.dataset.id;
            const icon = this.querySelector('svg');

            // Optimistic UI update
            const isActive = this.classList.toggle('active');
            if (isActive) {
                icon.classList.add('fill-moon-gold', 'text-moon-gold');
            } else {
                icon.classList.remove('fill-moon-gold', 'text-moon-gold');
            }

            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_id: productId })
            })
                .then(response => response.json())
                .then(data => {
                    updateWishlistBadge(data.count);
                })
                .catch(error => {
                    console.error('Error:', error);
                    // revert UI on error
                    this.classList.toggle('active');
                    if (isActive) {
                        icon.classList.remove('fill-moon-gold', 'text-moon-gold');
                    } else {
                        icon.classList.add('fill-moon-gold', 'text-moon-gold');
                    }
                });
        });
    });
});
