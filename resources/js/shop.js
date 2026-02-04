import { toggleBackdrop } from './ui-helpers';

document.addEventListener('DOMContentLoaded', function () {
    const filterToggle = document.getElementById('filter-toggle');
    const filterClose = document.getElementById('filter-close');
    const sidebar = document.getElementById('shop-sidebar');
    const backdrop = document.getElementById('backdrop');
    const applyBtn = document.getElementById('apply-filters');
    const filterForm = document.getElementById('filter-form');
    const sortSelect = document.querySelector('select[name="sort"]');

    // --- Sidebar Logic ---
    if (filterToggle && sidebar) {
        filterToggle.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            toggleBackdrop(true);
        });
    }

    function closeFilters() {
        if (sidebar) sidebar.classList.add('-translate-x-full');
        toggleBackdrop(false);
    }

    if (filterClose) filterClose.addEventListener('click', closeFilters);
    if (backdrop) backdrop.addEventListener('click', closeFilters);

    // --- Sort Logic (Intercept Change) ---
    // We removed inline onchange="this.form.submit()" to ensure this logic runs
    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            // Dispatch a submit event on the form so the Clean URL handler catches it
            if (filterForm) {
                const event = new Event('submit', { cancelable: true });
                filterForm.dispatchEvent(event);
            }
        });
    }

    // --- Clean URL Logic ---
    if (filterForm) {
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(filterForm);
            const searchParams = new URLSearchParams();

            // 1. Strings (Search, Sort, InStock)
            const search = formData.get('search');
            if (search && search.trim() !== '') {
                searchParams.set('search', search.trim());
            }

            const sort = formData.get('sort');
            if (sort) {
                searchParams.set('sort', sort);
            }

            if (formData.get('in_stock')) {
                searchParams.set('in_stock', '1');
            }

            // 2. Arrays -> Comma Separated Strings
            const arrayFields = [
                { input: 'category[]', param: 'category' },
                { input: 'brand[]', param: 'brand' },
                { input: 'concentration[]', param: 'concentration' },
                { input: 'capacity[]', param: 'capacity' },
                { input: 'price_range[]', param: 'price_range' },
                { input: 'notes[]', param: 'notes' }
            ];

            arrayFields.forEach(({ input, param }) => {
                const checkedElements = filterForm.querySelectorAll(`input[name="${input}"]:checked`);
                const values = Array.from(checkedElements).map(el => el.value);

                if (values.length > 0) {
                    searchParams.set(param, values.join(','));
                }
            });

            // 3. Navigation
            // Decode encoded commas (%2C) to actual commas (,) for cleaner URL
            const queryString = searchParams.toString().replace(/%2C/g, ',');
            window.location.href = `${window.location.pathname}?${queryString}`;
        });
    }
});
