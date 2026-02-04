import { toggleBackdrop } from './ui-helpers';

document.addEventListener('DOMContentLoaded', function () {
    const filterToggle = document.getElementById('filter-toggle');
    const filterClose = document.getElementById('filter-close');
    const sidebar = document.getElementById('shop-sidebar');
    const backdrop = document.getElementById('backdrop');
    // const applyBtn = document.getElementById('apply-filters'); // Removed unused
    const filterForm = document.getElementById('filter-form');
    const sortSelect = document.querySelector('select[name="sort"]');

    // Brand Filter Elements
    const brandSearchInput = document.getElementById('brand-search');
    const brandList = document.getElementById('brand-list');
    const brandShowMoreBtn = document.getElementById('brand-show-more');
    const INITIAL_VISIBLE_COUNT = 8;
    let isBrandExpanded = false;

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

    // --- Brand Search & Truncation Logic ---
    if (brandList) {
        const brandItems = Array.from(brandList.querySelectorAll('.brand-item'));

        function updateBrandVisibility() {
            const searchTerm = brandSearchInput ? brandSearchInput.value.toLowerCase().trim() : '';
            let visibleCount = 0;

            brandItems.forEach((item, index) => {
                const name = item.dataset.name;
                const matchesSearch = name.includes(searchTerm);

                // If searching, show all matches.
                // If not searching, respect "Show More" logic (id < limit or expanded).
                // Always show checked items to prevent hiding active filters.
                const isChecked = item.querySelector('input').checked;

                if (searchTerm !== '') {
                    // Search Mode
                    if (matchesSearch) {
                        item.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden');
                    }
                } else {
                    // Default Mode
                    // Always show if checked, otherwise respect truncation
                    if (isChecked || isBrandExpanded || visibleCount < INITIAL_VISIBLE_COUNT) {
                        item.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden');
                    }
                }
            });

            // Toggle Show More Button
            if (brandShowMoreBtn) {
                // Hide if searching OR if all items are naturally visible
                const totalItems = brandItems.length;
                if (searchTerm !== '' || totalItems <= INITIAL_VISIBLE_COUNT) {
                    brandShowMoreBtn.classList.add('hidden');
                } else {
                    brandShowMoreBtn.classList.remove('hidden');
                    brandShowMoreBtn.textContent = isBrandExpanded ? '- Show Less' : `+ Show More`;
                }
            }
        }

        // Initialize state
        updateBrandVisibility();

        if (brandSearchInput) {
            brandSearchInput.addEventListener('input', updateBrandVisibility);
        }

        if (brandShowMoreBtn) {
            brandShowMoreBtn.addEventListener('click', () => {
                isBrandExpanded = !isBrandExpanded;
                updateBrandVisibility();
            });
        }
    }


    // --- Sort Logic (Intercept Change) ---
    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
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
