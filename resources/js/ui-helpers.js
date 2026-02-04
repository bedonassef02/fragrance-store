/**
 * UI Helper Functions
 * 
 * Shared utilities for managing common UI patterns like Toasts, Modals, and Backdrops.
 */

// --- Constants ---
const TOAST_ID = 'toast';
const BACKDROP_ID = 'backdrop';

// --- Toast Notifications ---
export function showToast(message, isError = false) {
    let toast = document.getElementById(TOAST_ID);

    // Create toast if it doesn't exist (fail-safe)
    if (!toast) {
        toast = document.createElement('div');
        toast.id = TOAST_ID;
        toast.className = 'fixed bottom-5 right-5 text-white px-6 py-3 rounded-lg shadow-lg transition-all duration-300 translate-y-20 opacity-0 z-50 pointer-events-none';
        document.body.appendChild(toast);
    }

    // Reset classes to base state
    toast.className = `fixed bottom-5 right-5 text-white px-6 py-4 rounded-sm shadow-xl transition-all duration-300 z-50 transform font-medium tracking-wide text-sm ${isError ? 'bg-red-600' : 'bg-charcoal'}`;

    toast.textContent = message;

    // Show
    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-20', 'opacity-0');
    });

    // Auto Hide
    if (toast.dismissTimeout) clearTimeout(toast.dismissTimeout);

    toast.dismissTimeout = setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
    }, 3000);
}

// --- Backdrop Management ---
export function toggleBackdrop(show) {
    const backdrop = document.getElementById(BACKDROP_ID);
    if (!backdrop) return;

    if (show) {
        backdrop.classList.remove('hidden');
        // Small delay to allow display:block to apply before opacity transition
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
        });
        document.body.classList.add('overflow-hidden');
    } else {
        backdrop.classList.add('opacity-0');
        document.body.classList.remove('overflow-hidden');

        // Wait for transition to finish before hiding
        setTimeout(() => {
            backdrop.classList.add('hidden');
        }, 300);
    }
}

// --- Generic Modal/Drawer Management ---
export function toggleModal(modalId, show) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    if (show) {
        modal.classList.remove('hidden');
        toggleBackdrop(true);

        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0', 'scale-95');
            modal.classList.add('opacity-100', 'scale-100');
        });
    } else {
        modal.classList.remove('opacity-100', 'scale-100');
        modal.classList.add('opacity-0', 'scale-95');

        toggleBackdrop(false);

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
}

// --- Loading State ---
export function setLoading(btnElement, isLoading, loadingText = 'Loading...', originalText = '') {
    if (!btnElement) return;

    if (isLoading) {
        btnElement.dataset.originalText = btnElement.innerText; // Save original text
        btnElement.disabled = true;
        btnElement.innerText = loadingText;
        btnElement.classList.add('opacity-75', 'cursor-not-allowed');
    } else {
        btnElement.disabled = false;
        btnElement.innerText = originalText || btnElement.dataset.originalText || 'Submit';
        btnElement.classList.remove('opacity-75', 'cursor-not-allowed');
    }
}
