import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
    }

    document.querySelectorAll('[data-toggle-password]').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.querySelector(btn.dataset.togglePassword);
            if (input) input.type = input.type === 'password' ? 'text' : 'password';
        });
    });

    const flash = document.getElementById('flash-message');
    if (flash) setTimeout(() => flash.remove(), 4000);
});
