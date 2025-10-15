// Menu mobile toggle - Menú lateral
const hamburgerMenu = document.getElementById('hamburgerMenu');
const mobileSidebar = document.getElementById('mobileSidebar');
const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
const closeSidebar = document.getElementById('closeSidebar');

hamburgerMenu.addEventListener('click', function() {
    mobileSidebar.classList.add('active');
    mobileMenuOverlay.classList.add('active');
    document.body.style.overflow = 'hidden'; // Prevenir scroll
});

closeSidebar.addEventListener('click', function() {
    mobileSidebar.classList.remove('active');
    mobileMenuOverlay.classList.remove('active');
    document.body.style.overflow = ''; // Restaurar scroll
});

mobileMenuOverlay.addEventListener('click', function() {
    mobileSidebar.classList.remove('active');
    mobileMenuOverlay.classList.remove('active');
    document.body.style.overflow = ''; // Restaurar scroll
});

// Cerrar menú al hacer clic en un enlace (opcional)
const mobileLinks = document.querySelectorAll('.mobile-categories a, .mobile-sidebar-footer a');
mobileLinks.forEach(link => {
    link.addEventListener('click', function() {
        mobileSidebar.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        document.body.style.overflow = ''; // Restaurar scroll
    });
});
// Script para el menú retráctil de categorías en ESCRITORIO
document.addEventListener('DOMContentLoaded', function() {
    const categoriesToggleDesktop = document.getElementById('categoriesToggleDesktop');
    const categoriesDropdownDesktop = document.getElementById('categoriesDropdownDesktop');

    if (categoriesToggleDesktop && categoriesDropdownDesktop) {
        categoriesToggleDesktop.addEventListener('click', function() {
            categoriesDropdownDesktop.classList.toggle('active');
            categoriesToggleDesktop.classList.toggle('active');
        });

        // Cerrar el menú al hacer clic fuera de él
        document.addEventListener('click', function(event) {
            if (!categoriesToggleDesktop.contains(event.target) && !categoriesDropdownDesktop.contains(event.target)) {
                categoriesDropdownDesktop.classList.remove('active');
                categoriesToggleDesktop.classList.remove('active');
            }
        });

        // Cerrar el menú al hacer clic en un enlace de categoría
        const categoryLinks = categoriesDropdownDesktop.querySelectorAll('a');
        categoryLinks.forEach(link => {
            link.addEventListener('click', function() {
                categoriesDropdownDesktop.classList.remove('active');
                categoriesToggleDesktop.classList.remove('active');
            });
        });
    }
});
