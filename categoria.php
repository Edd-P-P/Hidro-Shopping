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

// Obtener información de la categoría - ESTA PARTE FALTABA
$sql_categoria = $con->prepare("SELECT id, nombre, slug, descripcion, color_fondo, color_titulo, texto_color, boton_primario, boton_secundario FROM categorias WHERE id = ? AND slug = ? AND activo = 1");
$sql_categoria->execute([$id, $slug]);
$categoria = $sql_categoria->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    header("Location: index.php");
    exit;
}

// Establecer valores por defecto
$categoria['descripcion'] = $categoria['descripcion'] ?? 'Descripción de ' . $categoria['nombre'];
$categoria['color_fondo'] = $categoria['color_fondo'] ?? '#ffffff';
$categoria['texto_color'] = $categoria['texto_color'] ?? '#000000';
$categoria['boton_primario'] = $categoria['boton_primario'] ?? '#007bff';
$categoria['boton_secundario'] = $categoria['boton_secundario'] ?? '#6c757d';

// Determinar imagen de hero dinámicamente
$imagen_hero = "Imagenes/hero/" . $categoria['id'] . ".png";
if (!file_exists($imagen_hero)) {
    $imagen_hero = "Imagenes/hero.png";
}

// Obtener productos de esta categoría
$sql_productos = $con->prepare("SELECT id, nombre, precio, categoria_id FROM productos WHERE activo = 1 AND categoria_id = ?");
$sql_productos->execute([$id]);
$productos = $sql_productos->fetchAll(PDO::FETCH_ASSOC);

// Obtener todas las categorías para el menú
$sql_todas_categorias = $con->prepare("SELECT id, nombre, slug FROM categorias WHERE activo = 1 ORDER BY id ASC");
$sql_todas_categorias->execute();
$todas_categorias = $sql_todas_categorias->fetchAll(PDO::FETCH_ASSOC);

// Función simple para ajustar brillo (solo para botones)
function adjustBrightness($hex, $steps) {
    if (empty($hex)) return '#3b82f6';
    
    $hex = str_replace('#', '', $hex);
    $steps = max(-255, min(255, $steps));
    
    if (strlen($hex) == 3) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    
    $r = hexdec(substr($hex,0,2));
    $g = hexdec(substr($hex,2,2));
    $b = hexdec(substr($hex,4,2));
    
    $r = max(0,min(255,$r + $steps));
    $g = max(0,min(255,$g + $steps));  
    $b = max(0,min(255,$b + $steps));
    
    return '#'.str_pad(dechex($r),2,'0',STR_PAD_LEFT)
           .str_pad(dechex($g),2,'0',STR_PAD_LEFT)
           .str_pad(dechex($b),2,'0',STR_PAD_LEFT);
}

// Calcular colores hover para botones
$color_primario_hover = adjustBrightness($categoria['boton_primario'], -20);
$color_secundario_hover = adjustBrightness($categoria['boton_secundario'], -20);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($categoria['nombre']); ?> - HidroBuy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
<style>
    .section-title{
        color: <?php echo $categoria['color_titulo']; ?>;
    }
    body {
        background-color: <?php echo $categoria['color_fondo']; ?>;
    }
    
    .hero-categoria {
        background: 
            /* Color semitransparente - ajusta el 0.3 para más/menos opacidad */
            linear-gradient(
                rgba(30, 58, 138, 0.2), 
                rgba(30, 58, 138, 0.2)
            ),
            /* Imagen de fondo */
            url('<?php echo $imagen_hero; ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: <?php echo $categoria['texto_color']; ?>;
        position: relative;
    }
    .hero-content{
        max-width: 1200px;
    }
    .hero-buttons {
        justify-content: center;
    }
    
    /* Si quieres que el color sea el de la categoría en lugar de azul fijo */
    .hero-categoria-con-color {
        background: 
            linear-gradient(
                rgba(<?php 
                    // Usar el color de la categoría con baja opacidad
                    $color = str_replace('#', '', $categoria['boton_primario']);
                    if(strlen($color) == 6) {
                        $r = hexdec(substr($color,0,2));
                        $g = hexdec(substr($color,2,2));
                        $b = hexdec(substr($color,4,2));
                        echo "$r, $g, $b, 0.2"; // Muy baja opacidad (0.2 = 20%)
                    } else {
                        echo "30, 58, 138, 0.3"; // Fallback azul
                    }
                ?>), 
                rgba(<?php 
                    if(strlen($color) == 6) {
                        echo "$r, $g, $b, 0.3"; // Un poco más de opacidad
                    } else {
                        echo "30, 58, 138, 0.3";
                    }
                ?>)
            ),
            url('<?php echo $imagen_hero; ?>');
        background-size: cover;
        background-position: center;
        color: <?php echo $categoria['texto_color']; ?>;
    }
    
    .btn-categoria-primario {
        background-color: <?php echo $categoria['boton_primario']; ?>;
        border-color: <?php echo $categoria['boton_primario']; ?>;
        color: white;
    }
    
    .btn-categoria-primario:hover {
        background-color: <?php echo $color_primario_hover; ?>;
        border-color: <?php echo $color_primario_hover; ?>;
        color: white;
    }
    
    .btn-categoria-secundario {
        background-color: <?php echo $categoria['boton_secundario']; ?>;
        border-color: <?php echo $categoria['boton_secundario']; ?>;
        color: white;
    }
    
    .btn-categoria-secundario:hover {
        background-color: <?php echo $color_secundario_hover; ?>;
        border-color: <?php echo $color_secundario_hover; ?>;
        color: white;
    }
    footer {
        margin-top: 20px;
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
                <?php foreach($todas_categorias as $cat): ?>
                    <li>
                        <a href="categoria.php?id=<?php echo $cat['id']; ?>&slug=<?php echo $cat['slug']; ?>">
                            <?php echo htmlspecialchars($cat['nombre']); ?>
                        </a>
                    </li>
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

    <!-- Hero Section Específico de la Categoría -->
    <section class="hero hero-categoria">
        <div class="container">
            <div class="hero-content">
                <h1><?php echo htmlspecialchars($categoria['nombre']); ?></h1>
                <p><?php echo nl2br(htmlspecialchars($categoria['descripcion'])); ?></p>
                <div class="hero-buttons">
                    <a href="#products" class="btn btn-categoria-primario">
                        <i class="fas fa-tools"></i> Explorar Productos
                    </a>
                    <a href="https://api.whatsapp.com/send/?phone=527712167150&text&type=phone_number&app_absent=0" target="_blank" class="btn btn-categoria-secundario">
                        <i class="fas fa-headset"></i> Asesoramiento Técnico
                    </a>
                </div>
            </div>
        </div> 
    </section>

    <!-- Products de la Categoría -->
    <section class="products" id="products">
        <div class="container">
            <h2 class="section-title">Productos de <?php echo htmlspecialchars($categoria['nombre']); ?></h2>
            
            <?php if (count($productos) > 0): ?>
                <div class="product-grid">
                    <?php foreach($productos as $row): ?>
                    <div class="product-card">
                        <?php
                        $id_producto = $row['id'];
                        $imagen = "Imagenes/productos/" . $categoria['id'] . "/" . $id_producto . ".png";
                        if (!file_exists($imagen)) {
                            $imagen = "Imagenes/productos/default/" . $id_producto . ".jpeg";
                            if (!file_exists($imagen)) {
                                $imagen = "Imagenes/default.png";
                            }
                        }
                        ?>
                        <div class="product-img">
                            <img src="<?php echo $imagen; ?>" alt="<?php echo $row['nombre']; ?>">
                        </div>
                        <div class="product-content">
                            <div class="product-info">
                                <h3><?php echo $row['nombre']; ?></h3>
                                <p class="product-price-index">$<?php echo number_format($row['precio'], 2); ?></p>
                            </div>
                            <div class="btn-action"> 
                                <a href="details.php?id=<?php echo $row['id']; ?>&categoria_id=<?php echo $row['categoria_id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" class="btn-det">Detalles</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-products">
                    <i class="fas fa-box-open fa-3x"></i>
                    <h3>Próximamente</h3>
                    <p>Estamos trabajando en agregar productos para esta categoría.</p>
                </div>
            <?php endif; ?>
        </div>         
    </section>

    <!-- Footer -->
    <footer id="contacto">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>HIDROSISTEMAS</h4>
                    <p>Especialistas en <?php echo htmlspecialchars($categoria['nombre']); ?> y soluciones hidráulicas.</p>
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
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="Nosotros.html">Nosotros</a></li>
                        <li><a href="#products">Productos</a></li>
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
    <script src="js/carrito.js"></script>
    <script>
    function addProducto(id, token, cantidad = 1) {  
        let url = 'clases/carrito.php';
        let formData = new FormData();
        formData.append('id', id);
        formData.append('token', token);
        formData.append('cantidad', cantidad);  

        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        })
        .then(response => response.json())
        .then(data => {
            if (data.ok) {
                let elemento = document.getElementById("num_cart");
                if (elemento) {
                    elemento.innerHTML = data.numero;
                }
                alert('Producto agregado al carrito');
            } else {
                alert('Error al agregar el producto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        });
    }
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
    }});
    </script>
</body>
</html>