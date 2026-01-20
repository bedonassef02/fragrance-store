import { toggleModal } from './ui-helpers';

document.addEventListener('DOMContentLoaded', () => {
    const modalId = 'size-guide-modal';
    const modal = document.getElementById(modalId);
    const closeBtn = document.getElementById('close-size-guide');
    const backdrop = document.getElementById('backdrop');
    const triggers = document.querySelectorAll('.size-guide-trigger');

    if (!modal) return;

    function openModal(e) {
        if (e) e.preventDefault();
        toggleModal(modalId, true);
    }

    function closeModal() {
        toggleModal(modalId, false);
    }

    triggers.forEach(trigger => {
        trigger.addEventListener('click', openModal);
    });

    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (backdrop) backdrop.addEventListener('click', closeModal);
});
