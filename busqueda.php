<?php
require_once 'config/config.php';
require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();

$id = $_GET['id'] ?? '';
$slug = $_GET['slug'] ?? '';

// Obtener información de la categoría
$sql_todas_categorias = $con->prepare("SELECT id, nombre, slug FROM categorias WHERE activo = 1 ORDER BY id ASC");
$sql_todas_categorias->execute();
$todas_categorias = $sql_todas_categorias->fetchAll(PDO::FETCH_ASSOC);

// Obtener información de la categoría de manera independiente
$sql_categoria = $con->prepare("SELECT id, nombre, slug, descripcion, color_fondo, color_titulo, texto_color, boton_primario, boton_secundario FROM categorias WHERE id = ? AND slug = ? AND activo = 1");
$sql_categoria->execute([$id, $slug]);
$categoria = $sql_categoria->fetch(PDO::FETCH_ASSOC);

// Parámetros de entrada
$busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';
$categoria_filtro = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$productos_por_pagina = 12;
$offset = ($pagina > 0) ? ($pagina - 1) * $productos_por_pagina : 0;

// Obtener categorías para el filtro
$sql_categorias = $con->prepare("SELECT id, nombre, slug FROM categorias WHERE activo = 1 ORDER BY id ASC");
$sql_categorias->execute();
$categorias = $sql_categorias->fetchAll(PDO::FETCH_ASSOC);

// Construir consulta base
$sql = "SELECT SQL_CALC_FOUND_ROWS p.id, p.nombre, p.precio, p.descuento, p.categoria_id, c.nombre AS categoria_nombre
        FROM productos p
        INNER JOIN categorias c ON p.categoria_id = c.id
        WHERE p.activo = 1";
$params = [];

if (!empty($busqueda)) {
    $sql .= " AND p.nombre LIKE :busqueda";
    $params[':busqueda'] = '%' . $busqueda . '%';
}

if ($categoria_filtro > 0) {
    $sql .= " AND p.categoria_id = :categoria_id";
    $params[':categoria_id'] = $categoria_filtro;
}

$sql .= " ORDER BY p.nombre ASC LIMIT :limite OFFSET :offset";
$params[':limite'] = $productos_por_pagina;
$params[':offset'] = $offset;

$stmt = $con->prepare($sql);
foreach ($params as $key => $value) {
    if ($key === ':limite' || $key === ':offset') {
        $stmt->bindValue($key, $value, PDO::PARAM_INT);
    } else {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }
}
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total de resultados (sin límite)
$totalStmt = $con->query("SELECT FOUND_ROWS() as total");
$total = (int)$totalStmt->fetch()['total'];
$total_paginas = ceil($total / $productos_por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de búsqueda - HidroBuy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos específicos para la página de búsqueda */
        .search-page {
            background-color: var(--light);
            min-height: 70vh;
            padding: 2rem 0;
        }
        
        .container-products {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .section-title {
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .results-count {
            text-align: center;
            color: var(--gray);
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }
        
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 1.5rem;
            justify-content: center;
        }
        
        .filter-badge {
            display: inline-flex;
            align-items: center;
            background: var(--primary);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            gap: 5px;
        }
        
        .filter-badge.remove {
            background: var(--gray);
            cursor: pointer;
        }
        
        .filter-badge.remove:hover {
            background: #555;
        }
        
        .filters-bar {
            background: var(--white);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }
        
        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .filter-group label {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.9rem;
        }
        
        .form-select, .form-control {
            padding: 10px 15px;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            font-size: 1rem;
            background: white;
            min-width: 200px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1rem;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: #030f3a;
        }
        
        .btn-outline-secondary {
            background: transparent;
            border: 1px solid var(--gray);
            color: var(--gray);
        }
        
        .btn-outline-secondary:hover {
            background: #f5f5f5;
        }
        
        .no-results {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray);
        }
        
        .no-results i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ddd;
        }
        
        .no-results p {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        
        .no-results .suggestions {
            margin-top: 20px;
            color: var(--gray);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            gap: 5px;
        }
        
        .pagination a, .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 14px;
            text-decoration: none;
            border: 1px solid var(--light-gray);
            color: var(--primary);
            background: white;
            border-radius: var(--border-radius);
            transition: all 0.3s;
            min-width: 40px;
        }
        
        .pagination a:hover {
            background: #f5f5f5;
        }
        
        .pagination .active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .form-select, .form-control {
                min-width: 100%;
            }
            
            .section-title {
                font-size: 1.6rem;
            }
        }
        
        @media (max-width: 576px) {
            .pagination a, .pagination span {
                padding: 6px 10px;
                min-width: 36px;
            }
        }
    </style>
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
                <?php foreach($categorias as $categoria): ?>
                    <li>
                        <a href="categoria.php?id=<?php echo $categoria['id']; ?>&slug=<?php echo $categoria['slug']; ?>">
                            <?php echo htmlspecialchars($categoria['nombre']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="mobile-sidebar-footer">
            <a href="#"><i class="fas fa-user"></i> Mi Cuenta</a>
            <a href="checkout.php" class="icon-wrapper"><i class="fas fa-shopping-cart"></i> Carrito</a>
            <a href="#"><i class="fas fa-phone"></i> Contacto</a>
        </div>
    </div>

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
            
            <!-- Barra de búsqueda -->
            <div class="search-bar">
                <form action="busqueda.php" method="GET">
                    <i class="fas fa-search"></i>
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Buscar productos..." 
                        value="<?php echo htmlspecialchars($busqueda); ?>"
                    >
                </form>
            </div>
            
            <div class="header-icons">
                <a href="#"><i class="fas fa-user"></i></a>
                <a href="checkout.php" class="icon-wrapper">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="num_cart" class="cart-count">0</span>
                </a>
            </div>
        </div>
    </header>

      <!-- Navegación de Categorías Retráctil - SOLO ESCRITORIO -->
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
                <?php foreach($todas_categorias as $cat): ?>
                    <li>
                        <a href="categoria.php?id=<?php echo $cat['id']; ?>&slug=<?php echo $cat['slug']; ?>">
                            <?php echo htmlspecialchars($cat['nombre']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>

    <!-- Navegación de Categorías Original (para móvil) -->
    <nav class="categories-nav">
        <div class="container categories-container">
            <button class="hamburger" id="hamburgerMenu">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="categories-list">
                <?php foreach($todas_categorias as $cat): ?>
                    <li>
                        <a href="categoria.php?id=<?php echo $cat['id']; ?>&slug=<?php echo $cat['slug']; ?>">
                            <?php echo htmlspecialchars($cat['nombre']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>

    <!-- Resultados de búsqueda -->
    <section class="search-page">
        <div class="container-products">
            <div class="page-header">
                <h2 class="section-title">Resultados de búsqueda</h2>
                
                <?php if ($total > 0): ?>
                    <div class="results-count">
                        Se encontraron <strong><?php echo $total; ?></strong> producto<?php echo $total !== 1 ? 's' : ''; ?>
                        <?php if (!empty($busqueda)): ?>
                            para "<strong><?php echo htmlspecialchars($busqueda); ?></strong>"
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Mostrar filtros activos -->
                <div class="active-filters">
                    <?php if (!empty($busqueda)): ?>
                        <span class="filter-badge">
                            Búsqueda: <?php echo htmlspecialchars($busqueda); ?>
                            <a href="?<?php echo http_build_query(array_filter(['categoria' => $categoria_filtro])); ?>" class="text-white ms-1">×</a>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($categoria_filtro > 0):
                        $cat_nombre = '';
                        foreach ($categorias as $c) {
                            if ($c['id'] == $categoria_filtro) {
                                $cat_nombre = $c['nombre'];
                                break;
                            }
                        }
                    ?>
                        <span class="filter-badge">
                            Categoría: <?php echo htmlspecialchars($cat_nombre); ?>
                            <a href="?<?php echo http_build_query(array_filter(['q' => $busqueda])); ?>" class="text-white ms-1">×</a>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($categoria_filtro || $busqueda): ?>
                        <a href="busqueda.php" class="filter-badge remove">Limpiar todos ×</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Filtros -->
            <div class="filters-bar">
                <form method="GET" class="filter-form">
                    <div class="filter-group">
                        <label for="categoria">Categoría:</label>
                        <select name="categoria" id="categoria" class="form-select">
                            <option value="">Todas las categorías</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($categoria_filtro == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="busqueda">Buscar:</label>
                        <input type="text" name="q" id="busqueda" placeholder="Buscar productos..." value="<?php echo htmlspecialchars($busqueda); ?>" class="form-control">
                    </div>
                    
                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary">Aplicar filtros</button>
                        <?php if ($categoria_filtro || $busqueda): ?>
                            <a href="busqueda.php" class="btn btn-outline-secondary" style="margin-top: 8px;">Limpiar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <?php if (empty($productos)): ?>
                <div class="no-results">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <p>No se encontraron productos que coincidan con tu búsqueda.</p>
                    <div class="suggestions">
                        <p>Sugerencias:</p>
                        <ul>
                            <li>Revisa la ortografía de las palabras</li>
                            <li>Utiliza términos más generales</li>
                            <li>Prueba con otras categorías</li>
                        </ul>
                    </div>
                </div>
            <?php else: ?>
                <!-- Manteniendo EXACTAMENTE tu estructura original de productos -->
                <div class="product-grid">
                    <?php foreach ($productos as $row): 
                        $precio = (float)$row['precio'];
                        $descuento = (float)$row['descuento'];
                        $precio_final = $descuento > 0 ? $precio - (($precio * $descuento) / 100) : $precio;

                        $id = $row['id'];
                        $categoria_id = $row['categoria_id'];
                        $imagen = "Imagenes/productos/{$categoria_id}/{$id}.PNG";
                        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $imagen)) {
                            $imagen = "Imagenes/default.png";
                        }

                        $token = hash_hmac('sha1', $id, KEY_TOKEN);
                    ?>
                    <div class="product-card">
                        <div class="product-img">
                            <img src="<?php echo $imagen; ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                        </div>
                        <div class="product-content">
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($row['nombre']); ?></h3>
                                <p class="product-price-index">
                                    <?php if ($descuento > 0): ?>
                                        <del>$<?php echo number_format($precio, 2); ?></del><br>
                                    <?php endif; ?>
                                    $<?php echo number_format($precio_final, 2); ?>
                                    <?php if ($descuento > 0): ?>
                                        <small class="text-success ms-2"><?php echo $descuento; ?>% OFF</small>
                                    <?php endif; ?>
                                </p>
                                <small class="text-muted"><?php echo htmlspecialchars($row['categoria_nombre']); ?></small>
                            </div>
                            <div class="btn-action"> 
                                <a href="details.php?id=<?php echo $id; ?>&categoria_id=<?php echo $categoria_id; ?>&token=<?php echo $token; ?>" class="btn-det">Detalles</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginación -->
                <?php if ($total_paginas > 1): ?>
                    <nav class="pagination">
                        <?php if ($pagina > 1): ?>
                            <a href="?<?php echo http_build_query(array_merge(['q' => $busqueda, 'categoria' => $categoria_filtro, 'pagina' => $pagina - 1])); ?>">
                                &laquo; Anterior
                            </a>
                        <?php endif; ?>

                        <?php for ($i = max(1, $pagina - 2); $i <= min($total_paginas, $pagina + 2); $i++): ?>
                            <a href="?<?php echo http_build_query(array_merge(['q' => $busqueda, 'categoria' => $categoria_filtro, 'pagina' => $i])); ?>" 
                               class="<?php echo ($i == $pagina) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($pagina < $total_paginas): ?>
                            <a href="?<?php echo http_build_query(array_merge(['q' => $busqueda, 'categoria' => $categoria_filtro, 'pagina' => $pagina + 1])); ?>">
                                Siguiente &raquo;
                            </a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>

            <?php endif; ?>

        </div>         
    </section>

    <!-- Footer -->
    <footer id="contacto">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>HIDROSISTEMAS</h4>
                    <p>Tubería de PVC, CPVC, FOFO, galvanizado, conduit, sanitario, alcantarillado, hidráulico, piezas especiales, válvulas y cementos.</p>
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

    <script src="js/app.js"></script>
    <script>
        document.getElementById("num_cart").textContent = localStorage.getItem('num_cart') || '0';
    </script>
</body>
</html>