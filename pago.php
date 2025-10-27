<?php
// Agregar session_start() al inicio
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/config.php';
require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();

// OBTENER CATEGORÍAS PARA EL MENÚ
$sql_todas_categorias = $con->prepare("SELECT id, nombre, slug FROM categorias WHERE activo = 1 ORDER BY id ASC");
$sql_todas_categorias->execute();
$todas_categorias = $sql_todas_categorias->fetchAll(PDO::FETCH_ASSOC);

$productos = [];
$total = 0;

if (!empty($_SESSION['carrito']['productos']) && is_array($_SESSION['carrito']['productos'])) {
    foreach ($_SESSION['carrito']['productos'] as $clave => $item) {
        $id = $item['id'];
        $cantidad = $item['cantidad'] ?? 1;
        $precio_usado = (float)($item['precio'] ?? 0);
        $medida_guardada = $item['medida'] ?? null;
        $requiere_medidas = (int)($item['requiere_medidas'] ?? 0);

        // Obtener nombre y categoría del producto
        $stmt = $con->prepare("SELECT nombre, categoria_id FROM productos WHERE id = ? AND activo = 1");
        $stmt->execute([$id]);
        $prod = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prod) {
            continue;
        }

        $nombre = $prod['nombre'];
        $categoria_id = $prod['categoria_id'];

        $medida_mostrar = 'No aplica';
        if ($requiere_medidas && $medida_guardada) {
            $medida_mostrar = $medida_guardada;
        }

        $precio_con_desc = $precio_usado;
        $subtotal = $cantidad * $precio_con_desc;

        $productos[] = [
            'clave' => $clave,
            'id' => $id,
            'nombre' => $nombre,
            'precio_mostrar' => $precio_usado,
            'precio_final' => $precio_con_desc,
            'cantidad' => $cantidad,
            'subtotal' => $subtotal,
            'categoria_id' => $categoria_id,
            'medida' => $medida_mostrar,
        ];

        $total += $subtotal;
    }
}

// Si el carrito está vacío, redirigir al carrito
if (empty($productos)) {
    header('Location: checkout.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago - HidroBuy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .bg-transparent {
            background-color: white !important;
        }
        /* Estilos para el resumen de pago */
        .order-summary, .payment-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .total-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            font-weight: bold;
            font-size: 1.2em;
        }
        #paypal-button-container {
            margin-top: 20px;
        }
        .loading, .success-message, .error-message {
            display: none;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
        }
        .loading {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
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
                <?php foreach($todas_categorias as $categoria): ?>
                    <li>
                        <a href="categoria.php?id=<?php echo $categoria['id']; ?>&slug=<?php echo $categoria['slug']; ?>" 
                           target="_blank">
                            <?php echo htmlspecialchars($categoria['nombre']); ?>
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
            <!-- Configuración para la barra de búsqueda -->
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
                    <a href="#" class="btn btn-success">
                        <i class="fas fa-user me-2"></i><?php echo $_SESSION['user_name']; ?>
                    </a>
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

    <!-- Contenido Principal -->
    <main class="container my-5">
        <h1 class="mb-4">Finalizar Compra</h1>

        <div class="row">
            <div class="col-md-8">
                <div class="order-summary">
                    <h2>Resumen del Pedido</h2>
                    <?php foreach ($productos as $producto): ?>
                    <div class="cart-item">
                        <div class="d-flex align-items-center">
                            <?php
                                $rutaImg = "imagenes/productos/" . $producto['categoria_id'] . '/' . $producto['id'] . ".png";
                                if (!file_exists($rutaImg)) {
                                    $rutaImg = "imagenes/productos/default.png";
                                }
                            ?>
                            <img src="<?php echo htmlspecialchars($rutaImg); ?>" 
                                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                                 width="60" 
                                 class="me-3 rounded"
                                 onerror="this.src='imagenes/productos/default.png'">
                            <div>
                                <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>
                                <div class="text-muted">Cantidad: <?php echo $producto['cantidad']; ?></div>
                                <div class="text-muted">Medida: <?php echo htmlspecialchars($producto['medida']); ?></div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div><?php echo MONEDA . number_format($producto['subtotal'], 2, '.', ','); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <div class="total-section">
                        <div class="cart-item">
                            <span>Total:</span>
                            <span id="total-amount"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="payment-section">
                    <h2>Método de Pago</h2>
                    <div id="paypal-button-container"></div>
                    
                    <div class="loading" id="loading">
                        <p>Procesando pago...</p>
                    </div>
                    
                    <div class="success-message" id="success-message">
                        <p>¡Pago completado con éxito! Redirigiendo...</p>
                    </div>
                    
                    <div class="error-message" id="error-message">
                        <p id="error-text">Ha ocurrido un error. Inténtalo de nuevo.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

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

    <!-- SDK de PayPal -->
    <script src="https://www.paypal.com/sdk/js?client-id=AeX5dn4cHn60Fpg2V_QumvmHNgITM-nv4eC_K_yoQC7uwzYR5hAqNHo6VS0kvOjNjjRpQ_VK1EBSVUib&currency=MXN"></script>
    
    <script>
        // Configurar botón de PayPal
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'paypal',
                height: 40
            },
            
            // Crear la transacción
            createOrder: function(data, actions) {
                const total = <?php echo $total; ?>;
                
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: total.toFixed(2),
                            currency_code: 'MXN'
                        },
                        description: 'Compra en Tienda Hidráulica'
                    }],
                    application_context: {
                        shipping_preference: 'NO_SHIPPING'
                    }
                });
            },
            
            // Finalizar la transacción
            onApprove: function(data, actions) {
                // Mostrar loading
                document.getElementById('loading').style.display = 'block';
                
                return actions.order.capture().then(function(details) {
                    // Ocultar loading
                    document.getElementById('loading').style.display = 'none';
                    
                    // Mostrar mensaje de éxito
                    document.getElementById('success-message').style.display = 'block';
                    
                    // Enviar datos al servidor
                    fetch('procesar_pedido.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            orderID: data.orderID,
                            payerID: data.payerID,
                            paymentID: details.purchase_units[0].payments.captures[0].id,
                            cart: <?php echo json_encode($productos); ?>,
                            total: <?php echo $total; ?>,
                            payer: details.payer
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Limpiar carrito después de una compra exitosa
                        // Redirigir a página de confirmación después de 2 segundos
                        setTimeout(function() {
                            window.location.href = 'orden_completada.php?id=' + data.orderId;
                        }, 2000);
                    })
                    .catch(error => {
                        console.error('Error al procesar pedido:', error);
                        // Aún así redirigir
                        setTimeout(function() {
                            window.location.href = 'orden_completada.php';
                        }, 2000);
                    });
                });
            },
            
            // Manejar cancelación
            onCancel: function(data) {
                document.getElementById('error-text').textContent = "Pago cancelado. Puedes intentarlo de nuevo.";
                document.getElementById('error-message').style.display = 'block';
            },
            
            // Manejar errores
            onError: function(err) {
                document.getElementById('error-text').textContent = "Ha ocurrido un error con PayPal. Inténtalo de nuevo.";
                document.getElementById('error-message').style.display = 'block';
                console.error('Error de PayPal:', err);
            }
            
        }).render('#paypal-button-container');
    </script>

    <script src="js/app.js"></script>
</body>
</html>