<?php
// Agregar session_start() al inicio
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/config.php';
require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();

// FORZAR LOGIN EN CHECKOUT
if (!isset($_SESSION['user_cliente'])) {
    header('Location: login.php?pago');
    exit;
}

// OBTENER CATEGORÍAS PARA EL MENÚ - ESTO FALTABA
$sql_todas_categorias = $con->prepare("SELECT id, nombre, slug FROM categorias WHERE activo = 1 ORDER BY id ASC");
$sql_todas_categorias->execute();
$todas_categorias = $sql_todas_categorias->fetchAll(PDO::FETCH_ASSOC);

$productos = [];
$total = 0;

// DEBUG: Ver contenido del carrito
echo "<!-- DEBUG Carrito: " . print_r($_SESSION['carrito'] ?? 'No hay carrito', true) . " -->";

if (!empty($_SESSION['carrito']['productos']) && is_array($_SESSION['carrito']['productos'])) {
    foreach ($_SESSION['carrito']['productos'] as $clave => $item) {
        // DEBUG: Ver item actual
        echo "<!-- DEBUG Item $clave: " . print_r($item, true) . " -->";
        
        $id = $item['id'];
        $cantidad = $item['cantidad'] ?? 1;
        $precio_usado = (float)($item['precio'] ?? 0);
        // CORRECIÓN: Usar 'medida' en lugar de 'medida_id'
        $medida_guardada = $item['medida'] ?? null;
        $requiere_medidas = (int)($item['requiere_medidas'] ?? 0);

        // Obtener nombre y categoría del producto
        $stmt = $con->prepare("SELECT nombre, categoria_id FROM productos WHERE id = ? AND activo = 1");
        $stmt->execute([$id]);
        $prod = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prod) {
            continue; // Producto eliminado o inactivo
        }

        $nombre = $prod['nombre'];
        $categoria_id = $prod['categoria_id'];

        // CORRECIÓN: Mostrar la medida guardada directamente
        $medida_mostrar = 'No aplica';
        if ($requiere_medidas && $medida_guardada) {
            $medida_mostrar = $medida_guardada;
        }

        // Calcular precio final y subtotal
        $precio_con_desc = $precio_usado; // Ya viene con descuento aplicado desde carrito.php
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - HidroBuy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="Imagenes/h.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        ul {
            padding-left: 1rem;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quantity-controls input {
            max-width: 70px;
            text-align: center;
        }
        .bg-transparent {
            background-color: white !important;
        }


        /* Mostrar solo en pantallas grandes */
        @media (min-width: 1024px) {
            .categories-nav-desktop {
                display: block;
            }
            
            /* Ocultar la navegación original de categorías en escritorio */
            .categories-nav {
                display: none;
            }
        }

        /* Para móviles, mostrar la navegación original */
        @media (max-width: 1023px) {
            .categories-nav-desktop {
                display: none;
            }
            
            .categories-nav {
                display: block;
            }
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
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

    <!-- Contenido del Carrito -->
    <main class="container my-5">
        <h1 class="mb-4">Tu Carrito de Compras</h1>

        <?php if (empty($productos)): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-shopping-cart fa-2x mb-3"></i>
                <h4>Tu carrito está vacío</h4>
                <p><a href="index.php" class="btn btn-primary">Seguir comprando</a></p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Medida</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td>
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
                                    <span><?php echo htmlspecialchars($producto['nombre']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($producto['medida']); ?></td>
                            <td><?php echo MONEDA . number_format($producto['precio_final'], 2, '.', ','); ?></td>
                            <td>
                                <!-- Formulario con clave única -->
                                <form id="form_actualizar_<?php echo htmlspecialchars($producto['clave']); ?>" style="display:inline;">
                                    <input type="hidden" name="clave" value="<?php echo htmlspecialchars($producto['clave']); ?>">
                                    <div class="d-flex align-items-center quantity-controls">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" 
                                            onclick="cambiarCantidad('<?php echo htmlspecialchars($producto['clave']); ?>', -1)">−</button>
                                        <input type="number" 
                                            name="cantidad"
                                            class="form-control text-center mx-2" 
                                            style="width: 60px;" 
                                            value="<?php echo $producto['cantidad']; ?>" 
                                            min="1" 
                                            max="99"
                                            onchange="actualizarCantidad('<?php echo htmlspecialchars($producto['clave']); ?>')">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" 
                                            onclick="cambiarCantidad('<?php echo htmlspecialchars($producto['clave']); ?>', 1)">+</button>
                                    </div>
                                </form>
                            </td>
                            <td><?php echo MONEDA . number_format($producto['subtotal'], 2, '.', ','); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger" 
                                    onclick="eliminarProducto('<?php echo htmlspecialchars($producto['clave']); ?>')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-8">
                    <a href="index.php" class="btn btn-secondary">Seguir comprando</a>
                </div>
                <div class="col-md-4 text-end">
                    <h3>Total: <?php echo MONEDA . number_format($total, 2, '.', ','); ?></h3>
                    <a href="pago.php" class="btn btn-success btn-lg mt-3">Proceder al Pago</a>
                </div>
            </div>
        <?php endif; ?>
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

<script>
    // Función para actualizar el contador del carrito
    function actualizarContadorCarrito() {
        const count = <?php echo count($productos); ?>;
        const numCartElement = document.getElementById('num_cart');
        if (numCartElement) {
            numCartElement.textContent = count;
        }
    }

    function eliminarProducto(clave) {
        if (confirm('¿Eliminar este producto del carrito?')) {
            let formData = new FormData();
            formData.append('clave', clave);

            fetch('clases/eliminar_carrito.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.ok) {
                    actualizarContadorCarrito();
                    location.reload();
                } else {
                    alert('Error: ' + (data.mensaje || 'No se pudo eliminar'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión');
            });
        }
    }

    function cambiarCantidad(clave, cambio) {
        const form = document.getElementById('form_actualizar_' + clave);
        const input = form.querySelector('input[name="cantidad"]');
        let valor = parseInt(input.value);

        if (cambio === -1 && valor === 1) {
            if (confirm('¿Eliminar este producto del carrito?')) {
                eliminarProducto(clave);
            }
            return;
        }

        valor += cambio;
        if (valor < 1) valor = 1;
        if (valor > 99) valor = 99;
        input.value = valor;
        actualizarCantidad(clave);
    }

    function actualizarCantidad(clave) {
        const form = document.getElementById('form_actualizar_' + clave);
        const formData = new FormData(form);

        fetch('clases/actualizar_carrito.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.ok) {
                actualizarContadorCarrito();
                location.reload();
            } else {
                alert('Error: ' + (data.mensaje || 'No se pudo actualizar'));
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        });
    }
    // Inicializar contador al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        actualizarContadorCarrito();
        
        // Script para el menú retráctil de categorías en ESCRITORIO
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
        }
    });
</script>
<script src="js/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>