<?php 
require_once 'config/config.php';
require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$categoria_id = isset($_GET['categoria_id']) ? (int)$_GET['categoria_id'] : 0;
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id <= 0 || $categoria_id <= 0 || empty($token)) {
    echo 'Error al procesar la petición';
    exit;
}

$token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
if (!hash_equals($token, $token_tmp)) {
    echo 'Error al procesar la petición';
    exit;
}

// Recordatorio que se deben agregar variables nuevas primero en el stmt para poder usarlas despues
$stmt = $con->prepare("
    SELECT id, nombre, descripcion, precio, descuento, stock, categoria_id, 
           especificaciones, tabla_med, requiere_medidas 
    FROM productos 
    WHERE id = ? AND categoria_id = ? AND activo = 1 
    LIMIT 1
");
$stmt->execute([$id, $categoria_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$especificaciones = !empty($row['especificaciones']) ? html_entity_decode($row['especificaciones'], ENT_QUOTES | ENT_HTML5, 'UTF-8') : '';
$tabla_med = !empty($row['tabla_med']) ? html_entity_decode($row['tabla_med'], ENT_QUOTES | ENT_HTML5, 'UTF-8') : '';


if (!$row) {
    echo 'Producto no encontrado o no disponible';
    exit;
}

$requiere_medidas = (int)($row['requiere_medidas'] ?? 0);
$stock_base = (int)($row['stock'] ?? 0); // ← Stock del producto base (cuando no requiere medidas)

// Obtener variantes si aplica
$variantes = [];
if ($requiere_medidas === 1) {
$stmtVar = $con->prepare("
    SELECT medida, precio_m AS precio, stock, descuento_m
    FROM productos_medidas 
    WHERE producto_id = ? 
    ORDER BY medida
");
    $stmtVar->execute([$id]);
    $variantes = $stmtVar->fetchAll(PDO::FETCH_ASSOC);
}

// Ahora sí, todo está validado
$nombre = htmlspecialchars($row['nombre']);
$descripcion = htmlspecialchars($row['descripcion']);
$precio = (float)$row['precio'];
$descuento = (float)$row['descuento'];
$precio_desc = $precio - (($precio * $descuento) / 100);

// Ruta de imagen
$dir_imagen = "imagenes/productos/";
$rutaImg = $dir_imagen . $categoria_id . '/' . $id . ".png";

if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $rutaImg)) {
    $rutaImg = $dir_imagen . "default.png";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HidroBuy </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css  ">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="  https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css  " rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css  ">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="tables.css">
</head>
<style>
    ul {
        padding-left: 1rem;
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
                <li><a href="CPVC_A.php" >CPVC agua caliente</a></li>
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
            <button href="#" type="button"><i class="fas fa-shopping-cart"></i> Carrito</button>
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
                <a href="index.php"><img src="Imagenes/logo-ajustado-2.png" alt="Logo Hidrosistemas" class="logo-hidrosistemas"></a>
               <a href="index.php"> <div class="logo">HIDROSISTEMAS</div></a>
            </div>
            
            <!-- Codigo para la busqueda de barra -->
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
                    <!-- Opcional: botón de envío (puedes ocultarlo si usas solo Enter) -->
                    <!-- <button type="submit" class="btn btn-link p-0 ms-2"><i class="fas fa-search"></i></button> -->
                </form>
            </div>
            
            <div class="header-icons">
                <a href="#"><i class="fas fa-user"></i></a>
                <a href="checkout.php" class="icon-wrapper">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="num_cart" class="cart-count"></span>
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
                <li><a href="CPVC_A.php" >CPVC agua caliente</a></li>
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

<!-- Products -->
<main>
    <div class="container-details">
            <div class="row">
                <!-- Columna de imagen del producto -->
                <div class="col-md-6 order-md-1">
                <img src="<?php echo $rutaImg; ?>" alt="<?php echo $nombre; ?>" class="product-image">
            </div>
            
            <!-- Columna de detalles del producto -->
            <div class="col-md-6 order-md-2">
            <h2 class="product-title"><?php echo $nombre; ?></h2>

           <?php if ($requiere_medidas === 1): ?>
            <!-- Botones de medidas -->
            <div class="measure-buttons mb-3">
                <label class="form-label fw-bold">Selecciona una medida:</label><br>
                <?php foreach ($variantes as $v): ?>
                    <?php 
                    $medida = htmlspecialchars($v['medida']);
                    $precio_m = (float)$v['precio']; // Precio base
                    $stock = (int)($v['stock'] ?? 0);
                    $descuento_variante = (float)($v['descuento_m'] ?? 0);
                    $precio_con_desc = $descuento_variante > 0 ? $precio_m - (($precio_m * $descuento_variante) / 100) : $precio_m;
                    $disabled = ($stock <= 0) ? 'disabled' : '';
                    ?>
                    <button type="button" 
                        class="btn btn-outline-secondary btn-sm me-2 mt-1 measure-btn <?php echo $disabled; ?>"
                        data-medida="<?php echo $medida; ?>"
                        data-precio="<?php echo $precio_m; ?>"
                        data-descuento="<?php echo $descuento_variante; ?>"
                        data-stock="<?php echo $stock; ?>"
                        <?php echo $disabled; ?>>
                        <?php echo $medida; ?>
                        <?php if ($stock <= 0): ?>
                            <span class="text-danger">(Agotado)</span>
                        <?php endif; ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Precio dinámico con descuento -->
            <?php if (!empty($variantes)): ?>
                <?php
                $primera = $variantes[0];
                $precio_inicial = (float)$primera['precio'];
                $descuento_inicial = (float)$primera['descuento_m'];
                $stock_inicial = (int)$primera['stock']; // ← ¡CORREGIDO!
                $precio_final = $descuento_inicial > 0 ? $precio_inicial - (($precio_inicial * $descuento_inicial) / 100) : $precio_inicial;
                ?>

                <div id="precios-container">
                    <p id="precio-original" class="text-muted mb-1"></p>
                    <h2 id="precio-dinamico" class="product-price"></h2>
                </div>

                <input type="hidden" id="precio-base" value="<?php echo $precio_inicial; ?>">
                <input type="hidden" id="descuento-seleccionado" value="<?php echo $descuento_inicial; ?>">
                <input type="hidden" id="medida-seleccionada" value="<?php echo htmlspecialchars($primera['medida']); ?>">
                <input type="hidden" id="stock-seleccionado" value="<?php echo $stock_inicial; ?>">
            <?php else: ?>
                <h2 class="product-price text-muted">Selecciona una medida</h2>
                <input type="hidden" id="precio-base" value="0">
                <input type="hidden" id="descuento-seleccionado" value="0">
                <input type="hidden" id="medida-seleccionada" value="">
                <input type="hidden" id="stock-seleccionado" value="0">
            <?php endif; ?>

            <?php else: ?>
            <!-- Producto sin medidas -->
            <?php if ($descuento > 0): ?>
                <p><del><?php echo MONEDA . number_format($precio, 2, '.'); ?></del></p>
                <h2 class="product-price">
                    <?php echo MONEDA . number_format($precio_desc, 2, '.'); ?>
                    <small class="text-success ms-2"><?php echo $descuento; ?>% OFF</small>
                </h2>
            <?php else: ?>
                <h2 class="product-price"><?php echo MONEDA . number_format($precio, 2, '.'); ?></h2>
            <?php endif; ?>
            <input type="hidden" id="precio-base" value="<?php echo $precio; ?>">
            <input type="hidden" id="descuento-seleccionado" value="<?php echo $descuento; ?>">
            <input type="hidden" id="medida-seleccionada" value="">
            <input type="hidden" id="stock-seleccionado" value="<?php echo $stock_base; ?>">
            <?php endif; ?>
                        <p class="product-description">
                            <?php echo html_entity_decode($descripcion, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>
                    </p>
                <!-- Mostrar stock disponible -->
                <div class="mt-2 text-muted">
                    <small>
                        <i class="fas fa-box"></i> 
                        <span id="stock-disponible">
                            <?php if ($requiere_medidas === 1): ?>
                                <?php echo !empty($variantes) ? 'Disponible: ' . $stock_inicial . ' unidades' : 'Selecciona una medida'; ?>
                            <?php else: ?>
                                Disponible: <?php echo $stock_base; ?> unidades
                            <?php endif; ?>
                        </span>
                    </small>
                </div>                
                <!-- Selector de cantidad (nueva sección añadida) -->
                <div class="quantity-selector">
                    <label for="quantity" style="margin-right: 15px; font-weight: 600;">Cantidad:</label>
                    <button class="quantity-btn" id="decrease">-</button>
                    <input type="number" id="quantity" class="quantity-input" value="1" min="1" max="99">
                    <button class="quantity-btn" id="increase">+</button>
                </div>
                
                <!-- Botones de acción -->
                <div class="d-grid gap-3 col-10 mx-auto action-buttons">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-bolt me-2"></i>Comprar ahora
                    </button>
                    <button class="btn btn-outline-primary" type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">
                        <i class="fas fa-shopping-cart me-2"></i>Agregar al carrito
                    </button>
                </div>


                
                <!-- Especificaciones y Tabla de Medidas -->
                <div class="specs-table-section mt-4">
                    <h4 class="section-title">Especificaciones Técnicas</h4>
                    <div class="row-table">
                        <!-- Columna izquierda: Especificaciones -->
                        <div class="col-md-6">
                            <?php if (!empty($especificaciones)): ?>
                                <div class="specs-content">
                                    <?php echo $especificaciones; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No hay especificaciones disponibles.</p>
                            <?php endif; ?>
                        </div>
                        <!-- Columna derecha: Tabla de medidas -->
                        <div class="col-md-6">
                            <?php if (!empty($tabla_med)): ?>
                                <div class="tabla-container">
                                    <div class="titulo-tabla">Tabla de Medidas</div>
                                    <?php echo $tabla_med; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No hay tabla de medidas disponible.</p>
                            <?php endif; ?>
                        </div>
                    </div>
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
                        <a href="  https://api.whatsapp.com/send/?phone=527712167150&text&type=phone_number&app_absent=0" target="_blank"><i class="fab fa-whatsapp"></i></a>
                        <a href="  https://www.instagram.com/hidrosistemas_mx?igsh=MXZ6YmsydjgxZmN6NA==" target="_blank"><i class="fab fa-instagram"></i></a>
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
document.addEventListener('DOMContentLoaded', function() {
    /* Funcionalidad de los botones para aumentar cantidad de productos */
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decrease');
    const increaseBtn = document.getElementById('increase');
   
    decreaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    });
    
    increaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue < 99) {
            quantityInput.value = currentValue + 1;
        }
    });
   
    quantityInput.addEventListener('change', function() {
        let value = parseInt(this.value);
        if (isNaN(value) || value < 1) {
            this.value = 1;
        } else if (value > 99) {
            this.value = 99;
        }
    });

    // --- Manejo de botones de medida ---
    const measureButtons = document.querySelectorAll('.measure-btn:not([disabled])');
    measureButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const medida = this.getAttribute('data-medida');
            const precio = parseFloat(this.getAttribute('data-precio'));
            const descuento = parseFloat(this.getAttribute('data-descuento'));
            const stock = parseInt(this.getAttribute('data-stock'));

            let precioFinal = precio;
            if (descuento > 0) {
                precioFinal = precio - (precio * descuento / 100);
            }
            const precioDinamico = document.getElementById('precio-dinamico');
            if (descuento > 0) {
                // Mostrar precio original tachado + precio final
                const precioOriginal = document.getElementById('precio-original');
                if (precioOriginal) {
                    precioOriginal.innerHTML = '<del>' + '<?php echo MONEDA; ?>' + precio.toFixed(2) + '</del>';
                }
                precioDinamico.innerHTML = '<?php echo MONEDA; ?>' + precioFinal.toFixed(2) +
                    '<small class="text-success ms-2">' + descuento + '% OFF</small>';
            } else {
                // Sin descuento: ocultar precio original y mostrar solo el precio
                document.getElementById('precio-original').innerHTML = '';
                precioDinamico.innerHTML = '<?php echo MONEDA; ?>' + precioFinal.toFixed(2);
            }
            document.getElementById('precio-base').value = precio;
            document.getElementById('descuento-seleccionado').value = descuento;
            document.getElementById('medida-seleccionada').value = medida;
            document.getElementById('stock-seleccionado').value = stock;

            // Actualizar texto de stock disponible
            document.getElementById('stock-disponible').textContent = 'Disponible: ' + stock + ' unidades';

            // Resaltar botón seleccionado
            document.querySelectorAll('.measure-btn').forEach(b => {
                b.classList.remove('btn-primary', 'btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-primary');
        });
    });

    // Resaltar el primer botón por defecto (si existe)
    const firstBtn = document.querySelector('.measure-btn:not([disabled])');
    if (firstBtn) {
        firstBtn.click();
    }

    // Mostrar stock inicial si no requiere medidas
    <?php if ($requiere_medidas === 0): ?>
        document.getElementById('stock-disponible').textContent = 'Disponible: <?php echo $stock_base; ?> unidades';
    <?php endif; ?>
}); 

// --- Función para agregar al carrito ---
function addProducto(id, token) {
    const medida = document.getElementById('medida-seleccionada').value;
    const stock = parseInt(document.getElementById('stock-seleccionado').value);
    const cantidad = parseInt(document.getElementById('quantity').value) || 1;

    // Validación universal: cantidad no debe exceder el stock
    if (cantidad > stock) {
        alert('Excedió el número de productos');
        return;
    }

    <?php if ($requiere_medidas === 1): ?>
        if (!medida) {
            alert('Por favor selecciona una medida.');
            return;
        }
        if (stock <= 0) {
            alert('Lo sentimos, no hay inventario disponible para esta medida.');
            return;
        }
    <?php endif; ?>

    let formData = new FormData();
    formData.append('id', id);
    formData.append('token', token);
    formData.append('cantidad', cantidad);
    <?php if ($requiere_medidas === 1): ?>
        formData.append('medida', medida);
        formData.append('precio', document.getElementById('precio-base').value);
        formData.append('descuento', document.getElementById('descuento-seleccionado').value);
    <?php endif; ?>

    fetch('clases/carrito.php', {
        method: 'POST',
        body: formData,
        mode: 'cors'
    })
    .then(response => response.json())
    .then(data => {
        if (data.ok) {
            document.getElementById("num_cart").textContent = data.numero;
            alert('Producto agregado al carrito');
        } else {
            alert('Error al agregar el producto: ' + (data.message || ''));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión');
    });
}
</script>
    <script src="js/carrito.js"></script>
</body>
</html>