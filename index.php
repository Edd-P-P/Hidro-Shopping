<?php
// Agregar session_start() al inicio
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/config.php';
require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();

// Obtener categorías para el menú
$sql_categorias = $con->prepare("SELECT id, nombre, slug FROM categorias WHERE activo = 1 ORDER BY id ASC");
$sql_categorias->execute();
$categorias = $sql_categorias->fetchAll(PDO::FETCH_ASSOC);

// Obtener productos destacados
$sql = $con->prepare("SELECT id, nombre, precio, categoria_id FROM productos WHERE activo = 1 AND categoria_id = 16");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

// VARIABLE PARA OCULTAR MENÚ RETRÁCTIL EN INDEX
$mostrar_menu_retractil = false;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HidroBuy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="#"><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?></a>
                <a href="compras.php"><i class="fas fa-shopping-bag"></i> Mis Compras</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php"><i class="fas fa-user"></i> Mi Cuenta</a>
            <?php endif; ?>
            <a href="checkout.php"><i class="fas fa-shopping-cart"></i> Carrito</a>
            <a href="#"><i class="fas fa-phone"></i> Contacto</a>
        </div>
    </div>

    <?php include 'menu.php'; ?>

    <!-- Hero -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Soluciones Hidráulicas para Profesionales</h1>
                <p>Somos distribuidores oficiales de las principales marcas del sector, ofreciendo productos de máxima calidad y rendimiento para tus proyectos más exigentes.</p>
                <div class="hero-buttons">
                    <a href="#products" class="btn btn-primary">
                        <i class="fas fa-tools"></i> Explorar Productos
                    </a>
                    <a href="https://api.whatsapp.com/send/?phone=527712167150&text&type=phone_number&app_absent=0" target="_blank" class="btn btn-secondary">
                        <i class="fas fa-headset"></i> Asesoramiento
                    </a>
                </div>
            </div>
        </div> 
    </section>

    <!-- Products -->
    <section class="products" id="products">
        <div class="container">
            <h2 class="section-title">Nuestras categorías</h2>
            <div class="product-grid">
                <?php foreach($resultado as $row): ?>
                <div class="product-card">
                    <?php
                    $id = $row['id'];
                    $imagen = "Imagenes/productos/1/". $id.".jpeg";
                    if (!file_exists($imagen)) {
                        $imagen = "Imagenes/default.png";
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
                            <button class="btn-prod" type="button" onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>', 1)">
                            Añadir al Carrito
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>         
    </section>

    <section class="features">
        <div class="container features-container">
            <div class="feature">
                <i class="fas fa-truck"></i>
                <h3>Envío Rápido</h3>
                <p>Entregas en 24-48 horas a toda la región</p>
            </div>
            <div class="feature">
                <i class="fas fa-shield-alt"></i>
                <h3>Garantía</h3>
                <p>Todos nuestros productos incluyen garantía</p>
            </div>
            <div class="feature">
                <i class="fas fa-headset"></i>
                <h3>Soporte</h3>
                <p>Asesoramiento técnico especializado</p>
            </div>
            <div class="feature">
                <i class="fas fa-undo"></i>
                <h3>Devoluciones</h3>
                <p>30 días para devoluciones sin problemas</p>
            </div>
        </div>
    </section>

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
                        <li><a href="index.php">Inicio</a></li>
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

    <div id="paypal-button-container"></div>

    <script src="js/app.js"></script>
    <script src="js/carrito.js"></script>
    <script>
        /*########### Funcionamiento carrito */
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>