<?php
require_once 'config/config.php';
require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();

// Verificar si hay productos en el carrito
$productos = [];
$total = 0;

if (!empty($_SESSION['carrito']['productos'])) {
    $ids = array_keys($_SESSION['carrito']['productos']);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    
    $sql = $con->prepare("SELECT id, nombre, precio, descuento, activo FROM productos WHERE id IN ($placeholders) AND activo = 1");
    $sql->execute($ids);
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultado as $row) {
        $id = $row['id'];
        $cantidad = $_SESSION['carrito']['productos'][$id];
        $precio = $row['precio'];
        $descuento = $row['descuento'];
        $precio_desc = $precio - (($precio * $descuento) / 100);

        $subtotal = $cantidad * ($descuento > 0 ? $precio_desc : $precio);

        $productos[] = [
            'id' => $id,
            'nombre' => $row['nombre'],
            'precio' => $precio,
            'descuento' => $descuento,
            'precio_final' => ($descuento > 0 ? $precio_desc : $precio),
            'cantidad' => $cantidad,
            'subtotal' => $subtotal
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

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
                <a href="index.php"><img src="Imagenes/logo-ajustado-2.png" alt="Logo Hidrosistemas" class="logo-hidrosistemas"></a>
                <a href="index.php"><div class="logo">HIDROSISTEMAS</div></a>
            </div>
            
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar productos.">
            </div>
            
            <div class="header-icons">
                <a href="#"><i class="fas fa-user"></i></a>
                <a href="#" class="icon-wrapper">
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
                <li><a href="#">CPVC agua caliente</a></li>
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
                            <th>Precio</th>
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
                                        $rutaImg = "imagenes/productos/" . $producto['id'] . ".jpeg";
                                        if (!file_exists($rutaImg)) {
                                            $rutaImg = "imagenes/productos/default.png";
                                        }
                                    ?>
                                    <img src="<?php echo $rutaImg; ?>" alt="<?php echo $producto['nombre']; ?>" width="60" class="me-3 rounded">
                                    <span><?php echo $producto['nombre']; ?></span>
                                </div>
                            </td>
                            <td><?php echo MONEDA . number_format($producto['precio_final'], 2, '.'); ?></td>
                            <td>
                                <div class="d-flex align-items-center quantity-controls">
                                    <button class="btn btn-outline-secondary btn-sm" onclick="cambiarCantidad(<?php echo $producto['id']; ?>, -1)">−</button>
                                    <input type="number" 
                                        id="cantidad_<?php echo $producto['id']; ?>" 
                                        class="form-control text-center mx-2" 
                                        style="width: 60px;" 
                                        value="<?php echo $producto['cantidad']; ?>" 
                                        min="1" 
                                        max="99"
                                        onchange="actualizarCantidad(<?php echo $producto['id']; ?>)">
                                    <button class="btn btn-outline-secondary btn-sm" onclick="cambiarCantidad(<?php echo $producto['id']; ?>, 1)">+</button>
                                </div>
                            </td>
                            <td><?php echo MONEDA . number_format($producto['subtotal'], 2, '.'); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger" onclick="eliminarProducto(<?php echo $producto['id']; ?>)">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <a href="index.php" class="btn btn-secondary">Seguir comprando</a>
                </div>
                <div class="col-md-4 text-end">
                    <h3>Total: <?php echo MONEDA . number_format($total, 2, '.'); ?></h3>
                    <button class="btn btn-success btn-lg mt-3">Proceder al Pago</button>
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

    <script src="js/carrito.js"></script>
    <script>
        function eliminarProducto(id) {
            if (confirm('¿Eliminar este producto del carrito?')) {
                let url = 'clases/eliminar_carrito.php';
                let formData = new FormData();
                formData.append('id', id);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        // Actualizar contador
                        let elemento = document.getElementById("num_cart");
                        if (elemento) {
                            elemento.innerHTML = data.numero;
                        }
                        // Recargar página para reflejar cambios
                        location.reload();
                    } else {
                        alert('Error al eliminar el producto');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error de conexión');
                });
            }
        }
function cambiarCantidad(id, cambio) {
    const input = document.getElementById('cantidad_' + id);
    let valor = parseInt(input.value) + cambio;
    if (valor < 1) valor = 1;
    if (valor > 99) valor = 99;
    input.value = valor;
    actualizarCantidad(id);
}

function actualizarCantidad(id) {
    const input = document.getElementById('cantidad_' + id);
    const nuevaCantidad = parseInt(input.value);

    if (nuevaCantidad < 1) {
        input.value = 1;
        return;
    }

    let url = 'clases/actualizar_carrito.php';
    let formData = new FormData();
    formData.append('id', id);
    formData.append('cantidad', nuevaCantidad);

    fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
    })
    .then(response => response.json())
    .then(data => {
        if (data.ok) {
            // Actualizar contador global
            let elemento = document.getElementById("num_cart");
            if (elemento) {
                elemento.innerHTML = data.numero;
            }
            // Recargar la página para actualizar precios y totales
            location.reload();
        } else {
            alert('Error al actualizar la cantidad');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión');
    });
}
    </script>
</body>
</html>