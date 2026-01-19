document.addEventListener('DOMContentLoaded', function () {
    const filterToggle = document.getElementById('filter-toggle');
    const filterClose = document.getElementById('filter-close');
    const sidebar = document.getElementById('shop-sidebar');
    const backdrop = document.getElementById('backdrop');
    const applyBtn = document.getElementById('apply-filters');

    if (!filterToggle || !sidebar) return;

    function openFilters() {
        sidebar.classList.remove('-translate-x-full');
        showBackdrop();
        document.body.style.overflow = 'hidden';
    }

    function closeFilters() {
        sidebar.classList.add('-translate-x-full');
        hideBackdrop();
        document.body.style.overflow = '';
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

    filterToggle.addEventListener('click', openFilters);
    if (filterClose) filterClose.addEventListener('click', closeFilters);
    if (applyBtn) applyBtn.addEventListener('click', closeFilters);

    if (backdrop) backdrop.addEventListener('click', closeFilters);
});
