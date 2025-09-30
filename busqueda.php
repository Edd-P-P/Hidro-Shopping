<?php
require_once 'config/config.php';
require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();

// Obtener término de búsqueda
$busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';
$productos = [];
$mensaje = '';

if (!empty($busqueda)) {
    $sql = "SELECT id, nombre, precio, descuento, categoria_id 
            FROM productos 
            WHERE activo = 1 
            AND nombre LIKE :busqueda 
            ORDER BY nombre ASC";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(':busqueda', '%' . $busqueda . '%', PDO::PARAM_STR);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($productos)) {
        $mensaje = "No se encontraron productos para: <strong>" . htmlspecialchars($busqueda) . "</strong>";
    }
} else {
    $mensaje = "Por favor ingresa un término de búsqueda.";
}
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
                <li><a href="#">Tubería PPR</a></li>
                <li><a href="#">Tubería galvanizada</a></li>
                <li><a href="#">Accesorios domésticos</a></li>
                <li><a href="#">Medidores y valvulas</a></li>
                <li><a href="#">Linea Sanitaria</a></li>
                <li><a href="#">Aspersores</a></li>
                <li><a href="#">Nebulizadores</a></li>
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
                        value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>"
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
                <li><a href="index.php">Volver al inicio</a></li>
                <li><a href="#">Tubería PPR</a></li>
                <li><a href="#">Tubería galvanizada</a></li>
                <li><a href="#">Accesorios domésticos</a></li>
                <li><a href="#">Medidores y valvulas</a></li>
                <li><a href="#">Linea Sanitaria</a></li>
                <li><a href="#">Aspersores</a></li>
                <li><a href="#">Nebulizadores</a></li>
            </ul>
        </div>
    </nav>

    <!-- Resultados de búsqueda -->
    <section class="products-CPVC_A" id="products">
        <div class="container-products">
            <h2 class="section-title">Resultados de búsqueda</h2>

            <?php if (!empty($busqueda)): ?>
                <p class="text-muted mb-4">Buscaste: <strong><?php echo htmlspecialchars($busqueda); ?></strong></p>
            <?php endif; ?>

            <?php if ($mensaje): ?>
                <div class="alert alert-info"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <?php if (!empty($productos)): ?>
                <div class="product-grid">
                    <?php foreach ($productos as $row): 
                        // Calcular precio final con descuento
                        $precio = (float)$row['precio'];
                        $descuento = (float)$row['descuento'];
                        $precio_final = $descuento > 0 ? $precio - (($precio * $descuento) / 100) : $precio;

                        // Ruta de imagen
                        $id = $row['id'];
                        $categoria_id = $row['categoria_id'];
                        $imagen = "Imagenes/productos/{$categoria_id}/{$id}.PNG";
                        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $imagen)) {
                            $imagen = "Imagenes/default.png";
                        }

                        // Token para enlace seguro
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
                            </div>
                            <div class="btn-action"> 
                                <a href="details.php?id=<?php echo $id; ?>&categoria_id=<?php echo $categoria_id; ?>&token=<?php echo $token; ?>" class="btn-det">Detalles</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>         
    </section>

    <!-- Footer -->
    <footer id="contacto">
        <div class="container-footer">
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

    <script src="js/app.js"></script>
    <script>
        // Si usas el carrito en otras páginas, puedes mantener esto
        document.getElementById("num_cart").textContent = localStorage.getItem('num_cart') || '0';
    </script>
</body>
</html>