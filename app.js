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

// Efecto de parallax suave
document.addEventListener('DOMContentLoaded', function() {
    const hero = document.querySelector('.hero');
    const content = document.querySelector('.hero-content');
    
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;
        hero.style.transform = 'translateY(' + rate + 'px)';
        content.style.transform = 'translateY(' + (rate * 0.5) + 'px)';
    });
    
    // Efecto de escritura para el título
    const heroTitle = document.querySelector('.hero h1');
    const originalText = heroTitle.innerHTML;
    heroTitle.innerHTML = '';
    
    let i = 0;
    const typeWriter = () => {
        if (i < originalText.length) {
            heroTitle.innerHTML += originalText.charAt(i);
            i++;
            setTimeout(typeWriter, 40);
        }
    };
    
    // Iniciar el efecto después de un breve retraso
    setTimeout(typeWriter, 500);
});