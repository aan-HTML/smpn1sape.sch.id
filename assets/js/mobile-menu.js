document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.getElementById('navToggle');
    const navClose = document.getElementById('navClose');
    const navMenu = document.getElementById('navMenu');
    const navOverlay = document.getElementById('navOverlay');

    const searchToggleBtn = document.getElementById('searchToggleBtn');
    const searchOverlay = document.getElementById('searchOverlay');
    const searchCloseBtn = document.getElementById('searchCloseBtn');
    const searchInput = searchOverlay ? searchOverlay.querySelector('input') : null;

    // Mobile Menu Toggle
    const openMenu = () => {
        if (!navMenu) return;
        navMenu.classList.add('open');
        if (navOverlay) navOverlay.classList.add('active');
        document.body.classList.add('menu-open');
    };
    const closeMenu = () => {
        if (!navMenu) return;
        navMenu.classList.remove('open');
        if (navOverlay) navOverlay.classList.remove('active');
        document.body.classList.remove('menu-open');
    };

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.contains('open') ? closeMenu() : openMenu();
        });
    }
    if (navClose) {
        navClose.addEventListener('click', closeMenu);
    }
    if (navOverlay) {
        navOverlay.addEventListener('click', closeMenu);
    }

    // Search Overlay Toggle
    if (searchToggleBtn && searchOverlay && searchCloseBtn) {
        searchToggleBtn.addEventListener('click', () => {
            searchOverlay.classList.add('active');
            if (searchInput) searchInput.focus();
        });
        searchCloseBtn.addEventListener('click', () => searchOverlay.classList.remove('active'));
    }
});