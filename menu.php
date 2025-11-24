<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$mostrar_menu_retractil = isset($mostrar_menu_retractil) ? $mostrar_menu_retractil : true;
?>

<!-- Top Bar -->
<div class="top-bar">
    <div class="container top-bar-container">
        <div class="top-links">
            <a href="#"><i class="fas fa-briefcase"></i> Servicios</a>
            <a href="#"><i class="fas fa-map-marker-alt"></i> Ubícanos</a>
        </div>
        <div class="help-link">
            <i class="fas fa-phone"></i>
            <span>Contáctanos 771 216 7150</span>
        </div>
    </div>
</div>

<!-- Header Principal -->
<header>
    <div class="container header-container">
        <div class="logo-container">            
            <a href="index.php">
               <img src="Imagenes/logo-ajustado-2.png" alt="Logo Hidrosistemas" class="logo-hidrosistemas">
            </a>
            <div class="logo">HIDROSISTEMAS</div>
        </div>
        <div class="search-bar">
            <form action="busqueda.php" method="GET" class="d-flex align-items-center" id="searchForm">
                <i class="fas fa-search me-2"></i>
                <input 
                    type="text" 
                    name="q" 
                    id="searchInput"
                    placeholder="Buscar productos..." 
                    class="form-control border-0 bg-transparent"
                    value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>"
                >
            </form>
        </div>
        <div class="header-icons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="compras.php"><i class="fas fa-shopping-bag me-2"></i>Mis compras</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-primary">Ingresar</a>
            <?php endif; ?>
            <a href="checkout.php" class="icon-wrapper">
                <i class="fas fa-shopping-cart"></i>
                <span id="num_cart" class="cart-count">0</span>
            </a>
        </div>
    </div>
</header>

<!-- Navegación de Categorías Retráctil - SOLO ESCRITORIO (CONDICIONAL) -->
<?php if ($mostrar_menu_retractil): ?>
<nav class="categories-nav-desktop">
    <button class="categories-toggle-desktop" id="categoriesToggleDesktop">
        <span><i class="fas fa-th-large me-2"></i> CATEGORÍAS</span>
        <i class="fas fa-chevron-down"></i>
    </button>
    <div class="categories-dropdown-desktop" id="categoriesDropdownDesktop">
        <div class="categories-dropdown-header">
            <a href="index.php" class="back-home-btn">
                <i class="fas fa-home me-2"></i> Volver al Inicio
            </a>
            <span class="categories-title">Todas Nuestras Categorías</span>
        </div>
        <ul class="categories-dropdown-list">
            <?php 
            $db = new Database();
            $con = $db->conectar();
            $sql_todas_categorias = $con->prepare("SELECT id, nombre, slug FROM categorias WHERE activo = 1 ORDER BY id ASC");
            $sql_todas_categorias->execute();
            $todas_categorias = $sql_todas_categorias->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($todas_categorias as $cat): ?>
                <li>
                    <a href="categoria.php?id=<?php echo $cat['id']; ?>&slug=<?php echo $cat['slug']; ?>">
                        <?php echo htmlspecialchars($cat['nombre']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
<?php endif; ?>

<!-- Navegación de Categorías Original (para móvil) -->
<nav class="categories-nav">
    <div class="container categories-container">
        <button class="hamburger" id="hamburgerMenu">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="categories-list">
            <?php 
            // Obtener categorías para el menú móvil
            $db = new Database();
            $con = $db->conectar();
            $sql_categorias = $con->prepare("SELECT id, nombre, slug FROM categorias WHERE activo = 1 ORDER BY id ASC");
            $sql_categorias->execute();
            $categorias = $sql_categorias->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($categorias as $cat): ?>
                <li>
                    <a href="categoria.php?id=<?php echo $cat['id']; ?>&slug=<?php echo $cat['slug']; ?>">
                        <?php echo htmlspecialchars($cat['nombre']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>

<script>
// Función para manejar la búsqueda
function realizarBusqueda() {
    const searchInput = document.getElementById('searchInput');
    const query = searchInput.value.trim();
    
    if (query === '') {
        alert('Por favor, ingresa un término de búsqueda');
        searchInput.focus();
        return false;
    }
    
    // Enviar el formulario
    document.getElementById('searchForm').submit();
    return true;
}

// Event listeners para la búsqueda
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const searchIcon = searchForm.querySelector('.fa-search');
    
    // Botón de búsqueda
    if (searchButton) {
        searchButton.addEventListener('click', function(e) {
            e.preventDefault();
            realizarBusqueda();
        });
    }
    
    // Icono de lupa (puede hacer clic también)
    if (searchIcon) {
        searchIcon.addEventListener('click', function(e) {
            e.preventDefault();
            realizarBusqueda();
        });
    }
    
    // Buscar al presionar Enter
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                realizarBusqueda();
            }
        });
    }
    
    // También puedes mantener el envío normal del formulario como respaldo
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const query = searchInput.value.trim();
            if (query === '') {
                e.preventDefault();
                alert('Por favor, ingresa un término de búsqueda');
                searchInput.focus();
            }
        });
    }
});
</script>

<style>


.btn-search:active {
    transform: translateY(0);
}

/* Hacer el icono de lupa clickeable */
.search-bar .fa-search {
    cursor: pointer;
    transition: color 0.3s ease;
}

.search-bar .fa-search:hover {
    color: var(--accent);
    transition: transform 0.2s ease;
    transform: rotate(1deg);
    transform: translateY(-6px);
}

/* Ajustar el espaciado en la barra de búsqueda */
.search-bar .d-flex {
    gap: 8px;
}
</style>