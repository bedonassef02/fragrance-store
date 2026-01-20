import { toggleBackdrop } from './ui-helpers';

document.addEventListener('DOMContentLoaded', function () {
    const filterToggle = document.getElementById('filter-toggle');
    const filterClose = document.getElementById('filter-close');
    const sidebar = document.getElementById('shop-sidebar');
    const backdrop = document.getElementById('backdrop');
    const applyBtn = document.getElementById('apply-filters');

    if (!filterToggle || !sidebar) return;

    function openFilters() {
        sidebar.classList.remove('-translate-x-full');
        toggleBackdrop(true);
    }

    function closeFilters() {
        sidebar.classList.add('-translate-x-full');
        toggleBackdrop(false);
    }

    filterToggle.addEventListener('click', openFilters);
    if (filterClose) filterClose.addEventListener('click', closeFilters);
    if (applyBtn) applyBtn.addEventListener('click', closeFilters);

    if (backdrop) backdrop.addEventListener('click', closeFilters);
});
