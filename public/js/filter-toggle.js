document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('filterToggle');
    const overlay = document.getElementById('subcategoryOverlay');
    const closeBtn = document.getElementById('closeOverlay');

    if (toggleBtn && overlay && closeBtn) {
        toggleBtn.addEventListener('click', () => {
            overlay.classList.add('active');
        });

        closeBtn.addEventListener('click', () => {
            overlay.classList.remove('active');
        });
    }
});