document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('size-guide-modal');
    const closeBtn = document.getElementById('close-size-guide');
    const backdrop = document.getElementById('backdrop');
    const triggers = document.querySelectorAll('.size-guide-trigger');

    if (!modal) return;

    function openModal(e) {
        if (e) e.preventDefault();
        modal.classList.remove('hidden');
        if (backdrop) {
            backdrop.classList.remove('hidden');
            setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
        }
        setTimeout(() => {
            modal.classList.remove('opacity-0', 'scale-95');
            modal.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closeModal() {
        modal.classList.remove('opacity-100', 'scale-100');
        modal.classList.add('opacity-0', 'scale-95');
        // We rely on other handlers to likely hide backdrop if they opened it?
        // But if we are the only one, we should hide it.
        // It's safe to request hide backdrop, but if another modal is open...
        // Assuming only one modal open at a time.
        if (backdrop) {
            backdrop.classList.add('opacity-0');
            setTimeout(() => backdrop.classList.add('hidden'), 300);
        }
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    triggers.forEach(trigger => {
        trigger.addEventListener('click', openModal);
    });

    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    // Check if backdrop exists before adding listener
    if (backdrop) backdrop.addEventListener('click', closeModal);
});
