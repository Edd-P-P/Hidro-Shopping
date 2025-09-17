<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HidroBuy</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- Overlay para menú móvil -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    
    <!-- Menú lateral móvil -->
    <div class="mobile-sidebar" id="mobileSidebar">
        <div class="mobile-sidebar-header">
            <div class="logo">Categorias</div>
            <button class="close-sidebar" id="closeSidebar">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mobile-categories">
            <ul>
                <li><a href="#">Cemento</a></li>
                <li><a href="#">Aceros</a></li>
                <li><a href="#">Materiales de Construcción</a></li>
                <li><a href="#">Acabados</a></li>
                <li><a href="#">Plomería</a></li>
                <li><a href="#">Material Eléctrico</a></li>
                <li><a href="#">Herramientas de Construcción</a></li>
                <li><a href="#">Ferretería</a></li>
            </ul>
        </div>
        
        <div class="mobile-sidebar-footer">
            <a href="#"><i class="fas fa-user"></i> Mi Cuenta</a>
            <a href="#"><i class="fas fa-shopping-cart"></i> Carrito</a>
            <a href="#"><i class="fas fa-phone"></i> Contacto</a>
        </div>
    </div>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container top-bar-container">
            <div class="top-links">
                <a href="#"><i class="fas fa-credit-card"></i> Financiamiento</a>
                <a href="#"><i class="fas fa-concierge-bell"></i> Servicios</a>
                <a href="#"><i class="fas fa-map-marker-alt"></i> Mapa de tiendas</a>
                <a href="#"><i class="fas fa-lightbulb"></i> Construtips</a>
                <a href="#"><i class="fas fa-gift"></i> Construganas</a>
            </div>
            <div class="help-link">
                <i class="fas fa-question-circle"></i>
                <span>¿Necesitas ayuda? Llámanos al 81 8300 2000</span>
            </div>
        </div>
    </div>

    <!-- Header Principal -->
    <header>
        <div class="container header-container">
            <div class="logo">            
                <img src="Imagenes/logo-ajustado-2.png" alt="Logo Hidrosistemas" class="logo-hidrosistemas">
                <img src="Imagenes/triangulo.png" alt="triangulo decorativo" class="triangulo">
            </div>
            
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar productos.">
            </div>
            
            <div class="header-icons">
                <a href="#"><i class="fas fa-user"></i></a>
                <a href="#" class="icon-wrapper">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">0</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Navegación de Categorías -->
    <nav class="categories-nav" id="categoriesNav">
        <div class="container categories-container">
            <button class="hamburger" id="hamburgerMenu">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="categories-list">
                <li><a href="#">Cemento</a></li>
                <li><a href="#">Aceros</a></li>
                <li><a href="#">Materiales de Construcción</a></li>
                <li><a href="#">Acabados</a></li>
                <li><a href="#">Plomería</a></li>
                <li><a href="#">Material Eléctrico</a></li>
                <li><a href="#">Herramientas de Construcción</a></li>
                <li><a href="#">Ferretería</a></li>
            </ul>
        </div>
    </nav>

    <!-- Footer -->
    <footer id="contacto">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>HIDROSISTEMAS</h4>
                    <p>Tenemos las mejores marcas, con calidad que buscas.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/HidrosistemasHGO/?fref=ts" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://api.whatsapp.com/send/?phone=527712167150&text&type=phone_number&app_absent=0" target="_blank"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://www.instagram.com/hidrosistemas_mx?igsh=MXZ6YmsydjgxZmN6NA==" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Servicios</h4>
                    <ul>
                        <li><a href="asesoria.html">Asesoría</a></li>
                        <li><a href="capacitacion.html">Capacitación</a></li>
                        <li><a href="supervision.html">Supervisión</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="index.html">Inicio</a></li>
                        <li><a href="Nosotros.html">Nosotros</a></li>
                        <li><a href="productos.html">Productos</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Hidrosistemas. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    <script src="app.js"></script>
</body>
</html>