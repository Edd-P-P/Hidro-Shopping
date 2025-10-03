<?php
require_once 'config/config.php';
require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();

// Parámetros de entrada
$busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';
$categoria_filtro = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$productos_por_pagina = 12;
$offset = ($pagina > 0) ? ($pagina - 1) * $productos_por_pagina : 0;

// Obtener categorías para el filtro
$stmtCat = $con->query("SELECT id, nombre FROM categorias WHERE activo = 1 ORDER BY nombre");
$categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

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
</head>

<style>
    :root{
        --primary-color: #1375BA;
        --secondary-color: #FFD54F;
        --accent-color: #FF9800;
        --text-color: #333;
        --background-color: #FFF9C4;
        --font-family: 'PT Sans', sans-serif;
        --font-family-alt: 'Montserrat', sans-serif;
    }
    body{
        background-color: var(--background-color);
    }
    .section-title {
        color: #1375BA;
    }
    .container-products{
        max-width: 1200px;
        margin: 25px auto;
        padding: 0 1rem;
    }
    .btn-secondary {
        background: transparent;
        border: 2px solid #1375BA;
        color: #1375BA;
    }
    .container-footer{
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        justify-content: center;
    }
    .filters-bar {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
        gap: 0.5rem;
    }
    .pagination a, .pagination span {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        text-decoration: none;
        border: 1px solid #ddd;
        color: #1375BA;
        background: white;
    }
    .pagination .active {
        background: #1375BA;
        color: white;
        border-color: #1375BA;
    }
    .no-results {
        text-align: center;
        padding: 2rem;
        color: #666;
    }
</style>

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
                <li><a href="index.php">Volver al inicio</a></li>
                <?php foreach ($categorias as $cat): ?>
                    <li><a href="busqueda.php?categoria=<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="mobile-sidebar-footer">
            <a href="#"><i class="fas fa-user"></i> Mi Cuenta</a>
            <button href="#"><i class="fas fa-shopping-cart"></i> Carrito</button>
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
                <form action="busqueda.php" method="GET" class="d-flex align-items-center">
                    <i class="fas fa-search me-2"></i>
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Buscar productos..." 
                        class="form-control border-0 bg-transparent"
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

    <!-- Navegación de Categorías -->
    <nav class="categories-nav">
        <div class="container categories-container">
            <button class="hamburger" id="hamburgerMenu">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="categories-list">
                <li><a href="index.php">Inicio</a></li>
                <?php foreach ($categorias as $cat): ?>
                    <li><a href="busqueda.php?categoria=<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>

    <!-- Filtros y resultados -->
    <section class="products-CPVC_A" id="products">
        <div class="container-products">
            <h2 class="section-title">Resultados de búsqueda</h2>

            <!-- Mostrar términos activos -->
            <div class="mb-3">
                <?php if (!empty($busqueda)): ?>
                    <span class="badge bg-primary me-2">Búsqueda: <?php echo htmlspecialchars($busqueda); ?></span>
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
                    <span class="badge bg-secondary me-2">Categoría: <?php echo htmlspecialchars($cat_nombre); ?></span>
                    <a href="busqueda.php<?php echo !empty($busqueda) ? '?q=' . urlencode($busqueda) : ''; ?>" class="badge bg-light text-dark">× Quitar filtro</a>
                <?php endif; ?>
            </div>

            <!-- Filtros -->
            <div class="filters-bar">
                <form method="GET" class="d-flex flex-wrap align-items-center gap-3">
                    <div>
                        <label><strong>Categoría:</strong></label>
                        <select name="categoria" class="form-select" style="width: auto; display: inline-block;">
                            <option value="">Todas</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($categoria_filtro == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <input type="text" name="q" placeholder="Buscar en resultados..." value="<?php echo htmlspecialchars($busqueda); ?>" class="form-control" style="width: 200px;">
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <?php if ($categoria_filtro || $busqueda): ?>
                        <a href="busqueda.php" class="btn btn-outline-secondary">Limpiar</a>
                    <?php endif; ?>
                </form>
            </div>

            <?php if (empty($productos)): ?>
                <div class="no-results">
                    <i class="fas fa-box-open fa-3x mb-3 text-muted"></i>
                    <p>No se encontraron productos.</p>
                </div>
            <?php else: ?>
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
                            <a href="?<?php echo http_build_query(array_filter(['q' => $busqueda, 'categoria' => $categoria_filtro, 'pagina' => $pagina - 1])); ?>">&laquo; Anterior</a>
                        <?php endif; ?>

                        <?php for ($i = max(1, $pagina - 2); $i <= min($total_paginas, $pagina + 2); $i++): ?>
                            <a href="?<?php echo http_build_query(array_filter(['q' => $busqueda, 'categoria' => $categoria_filtro, 'pagina' => $i])); ?>" 
                               class="<?php echo ($i == $pagina) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($pagina < $total_paginas): ?>
                            <a href="?<?php echo http_build_query(array_filter(['q' => $busqueda, 'categoria' => $categoria_filtro, 'pagina' => $pagina + 1])); ?>">Siguiente &raquo;</a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>

            <?php endif; ?>

        </div>         
    </section>

    <!-- Footer -->
    <footer id="contacto">
        <div class="container-footer">
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