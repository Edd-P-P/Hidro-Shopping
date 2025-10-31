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
            <img src="Imagenes/logo-ajustado-2.png" alt="Logo Hidrosistemas" class="logo-hidrosistemas">
            <div class="logo">HIDROSISTEMAS</div>
        </div>
        <div class="search-bar">
            <form action="busqueda.php" method="GET" class="d-flex align-items-center">
                <i class="fas fa-search me-2"></i>
                <input 
                    type="text" 
                    name="q" 
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