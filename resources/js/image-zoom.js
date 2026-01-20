document.addEventListener('DOMContentLoaded', () => {
    const zoomModal = document.getElementById('zoom-modal');
    const zoomImgFull = document.getElementById('zoom-img-full');
    const closeZoom = document.getElementById('close-zoom');

    if (zoomModal && zoomImgFull) {
        document.body.addEventListener('click', function (e) {
            if (e.target.matches('.cursor-zoom-in')) {
                zoomImgFull.src = e.target.src;
                zoomModal.classList.remove('hidden');
                zoomModal.classList.add('flex'); // Ensure flex is added
                requestAnimationFrame(() => {
                    zoomModal.classList.remove('opacity-0');
                });
                document.body.classList.add('overflow-hidden');
            }
        });

        const close = () => {
            zoomModal.classList.add('opacity-0');
            document.body.classList.remove('overflow-hidden');
            setTimeout(() => {
                zoomModal.classList.add('hidden');
                zoomModal.classList.remove('flex');
            }, 300);
        };

        zoomModal.addEventListener('click', close);

        if (closeZoom) {
            closeZoom.addEventListener('click', (e) => {
                e.stopPropagation();
                close();
            });
        }
    }
});
