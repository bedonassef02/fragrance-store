export function updateCartBadge(count) {
    const badges = document.querySelectorAll('.cart-count-badge');
    badges.forEach(b => {
        b.innerText = count;
        if (count > 0) {
            b.classList.remove('hidden');
        } else {
            b.classList.add('hidden');
        }
    });
}
