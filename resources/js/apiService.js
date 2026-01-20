const apiService = {
    _getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    },

    async _fetch(url, options) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this._getCsrfToken(),
                'Accept': 'application/json',
            },
        };

        const mergedOptions = { ...defaultOptions, ...options };
        mergedOptions.headers = { ...defaultOptions.headers, ...options.headers };

        try {
            const response = await fetch(url, mergedOptions);
            const data = await response.json();

            if (!response.ok) {
                return { success: false, error: data.message || `Request failed with status ${response.status}`, status: response.status, data: data };
            }

            return { success: true, data: data };
        } catch (error) {
            console.error(`[ApiService] Fetch error for ${url}:`, error);
            return { success: false, error: 'A network error occurred.' };
        }
    },

    addToCart(variantId, quantity) {
        return this._fetch('/cart/add', {
            method: 'POST',
            body: JSON.stringify({
                product_variant_id: variantId,
                quantity: quantity,
            }),
        });
    },

    updateCart(variantId, quantity) {
        return this._fetch('/cart/update', {
            method: 'PATCH',
            body: JSON.stringify({
                id: variantId,
                quantity: quantity,
            }),
        });
    },

    removeFromCart(variantId) {
        return this._fetch('/cart/remove', {
            method: 'DELETE',
            body: JSON.stringify({
                id: variantId,
            }),
        });
    },

    applyCoupon(code) {
        return this._fetch('/cart/coupon', {
            method: 'POST',
            body: JSON.stringify({ code }),
        });
    },

    removeCoupon() {
        return this._fetch('/cart/coupon/remove', { method: 'POST' });
    },
};

export default apiService;
