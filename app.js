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