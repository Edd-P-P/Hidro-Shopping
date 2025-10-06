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

// Obtener información del producto base
$stmt = $con->prepare("
    SELECT id, nombre, descripcion, precio, descuento, stock, categoria_id, 
           especificaciones, tabla_med, requiere_medidas 
    FROM productos 
    WHERE id = ? AND categoria_id = ? AND activo = 1 
    LIMIT 1
");
$stmt->execute([$id, $categoria_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo 'Producto no encontrado o no disponible';
    exit;
}

$especificaciones = !empty($row['especificaciones']) ? html_entity_decode($row['especificaciones'], ENT_QUOTES | ENT_HTML5, 'UTF-8') : '';
$tabla_med = !empty($row['tabla_med']) ? html_entity_decode($row['tabla_med'], ENT_QUOTES | ENT_HTML5, 'UTF-8') : '';

$requiere_medidas = (int)($row['requiere_medidas'] ?? 0);
$stock_base = (int)($row['stock'] ?? 0);

// CORRECIÓN: Obtener las medidas directamente de productos_medidas
$variantes = [];
if ($requiere_medidas === 1) {
    $stmtVar = $con->prepare("
        SELECT medida_id, precio_m AS precio, stock_m, descuento_m
        FROM productos_medidas 
        WHERE producto_id = ? 
        ORDER BY medida_id
    ");
    $stmtVar->execute([$id]);
    $variantes = $stmtVar->fetchAll(PDO::FETCH_ASSOC);
}

$nombre = htmlspecialchars($row['nombre']);
$descripcion = htmlspecialchars($row['descripcion']);
$precio = (float)$row['precio'];
$descuento = (float)$row['descuento'];
$precio_desc = $precio - (($precio * $descuento) / 100);

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
            <!-- Botones de medidas CORREGIDOS -->
            <div class="measure-buttons mb-3">
                <label class="form-label fw-bold">Selecciona una medida:</label><br>
                <?php foreach ($variantes as $index => $v): ?>
                    <?php 
                    // CORRECIÓN: Usar medida_id directamente como texto de la medida
                    $medida_texto = htmlspecialchars($v['medida_id']);
                    $precio_m = (float)$v['precio'];
                    $stock = (int)($v['stock_m'] ?? 0);
                    $descuento_variante = (float)($v['descuento_m'] ?? 0);
                    $disabled = ($stock <= 0) ? 'disabled' : '';
                    ?>
                    <button type="button" 
                        class="btn btn-outline-secondary btn-sm me-2 mt-1 measure-btn <?php echo $disabled; ?>"
                        data-medida-texto="<?php echo $medida_texto; ?>"
                        data-precio="<?php echo $precio_m; ?>"
                        data-descuento="<?php echo $descuento_variante; ?>"
                        data-stock="<?php echo $stock; ?>"
                        <?php echo $disabled; ?>>
                        <?php echo $medida_texto; ?>
                        <?php if ($stock <= 0): ?>
                            <span class="text-danger">(Agotado)</span>
                        <?php endif; ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Precio dinámico con descuento CORREGIDO -->
            <?php if (!empty($variantes)): ?>
                <?php
                    $primera = $variantes[0];
                    $precio_inicial = (float)$primera['precio'];
                    $descuento_inicial = (float)($primera['descuento_m'] ?? 0);
                    $stock_inicial = (int)($primera['stock_m'] ?? 0);
                    $medida_texto_inicial = htmlspecialchars($primera['medida_id']);
                    $precio_final = $descuento_inicial > 0 
                        ? $precio_inicial - (($precio_inicial * $descuento_inicial) / 100) 
                        : $precio_inicial;
                ?>

                <div id="precios-container">
                    <?php if ($descuento_inicial > 0): ?>
                        <p id="precio-original" class="text-muted mb-1">
                            <del><?php echo MONEDA . number_format($precio_inicial, 2, '.'); ?></del>
                        </p>
                    <?php else: ?>
                        <p id="precio-original" class="text-muted mb-1" style="display:none;"></p>
                    <?php endif; ?>
                    <h2 id="precio-dinamico" class="product-price">
                        <?php echo MONEDA . number_format($precio_final, 2, '.'); ?>
                        <?php if ($descuento_inicial > 0): ?>
                            <small class="text-success ms-2"><?php echo $descuento_inicial; ?>% OFF</small>
                        <?php endif; ?>
                    </h2>
                </div>

                <!-- Campos ocultos CORREGIDOS -->
                <input type="hidden" id="medida-texto-seleccionada" value="<?php echo $medida_texto_inicial; ?>">
                <input type="hidden" id="precio-base" value="<?php echo $precio_inicial; ?>">
                <input type="hidden" id="descuento-seleccionado" value="<?php echo $descuento_inicial; ?>">
                <input type="hidden" id="stock-seleccionado" value="<?php echo $stock_inicial; ?>">

            <?php else: ?>
                <h2 class="product-price text-muted">No hay medidas disponibles</h2>
                <input type="hidden" id="precio-base" value="0">
                <input type="hidden" id="descuento-seleccionado" value="0">
                <input type="hidden" id="medida-texto-seleccionada" value="">
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
            <input type="hidden" id="medida-texto-seleccionada" value="">
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
    console.log("ID del producto:", <?php echo $id; ?>);
    console.log("Token generado:", "<?php echo $token_tmp; ?>");
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad del contador de cantidad
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

    // --- Manejo de botones de medida CORREGIDO ---
    const measureButtons = document.querySelectorAll('.measure-btn:not([disabled])');
    measureButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const medidaTexto = this.getAttribute('data-medida-texto');
            const precio = parseFloat(this.getAttribute('data-precio'));
            const descuento = parseFloat(this.getAttribute('data-descuento'));
            const stock = parseInt(this.getAttribute('data-stock'));

            let precioFinal = precio;
            if (descuento > 0) {
                precioFinal = precio - (precio * descuento / 100);
            }

            // Actualizar precios en la UI
            const precioDinamico = document.getElementById('precio-dinamico');
            const precioOriginal = document.getElementById('precio-original');
            
            if (descuento > 0) {
                if (precioOriginal) {
                    precioOriginal.innerHTML = '<del>' + '<?php echo MONEDA; ?>' + precio.toFixed(2) + '</del>';
                    precioOriginal.style.display = 'block';
                }
                precioDinamico.innerHTML = '<?php echo MONEDA; ?>' + precioFinal.toFixed(2) +
                    '<small class="text-success ms-2">' + descuento + '% OFF</small>';
            } else {
                if (precioOriginal) precioOriginal.style.display = 'none';
                precioDinamico.innerHTML = '<?php echo MONEDA; ?>' + precioFinal.toFixed(2);
            }

            // Actualizar campos ocultos
            document.getElementById('precio-base').value = precio;
            document.getElementById('descuento-seleccionado').value = descuento;
            document.getElementById('medida-texto-seleccionada').value = medidaTexto;
            document.getElementById('stock-seleccionado').value = stock;

            // Actualizar texto de stock disponible
            document.getElementById('stock-disponible').textContent = 'Disponible: ' + stock + ' unidades';

            // Resaltar botón seleccionado
            document.querySelectorAll('.measure-btn').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-secondary');
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

// --- Función para agregar al carrito CON MEJOR DEPURACIÓN ---
function addProducto(id, token) {
    const medidaTexto = document.getElementById('medida-texto-seleccionada').value;
    const stock = parseInt(document.getElementById('stock-seleccionado').value);
    const cantidad = parseInt(document.getElementById('quantity').value) || 1;
    const precio = parseFloat(document.getElementById('precio-base').value);
    const descuento = parseFloat(document.getElementById('descuento-seleccionado').value);

    console.log("=== DATOS PARA AGREGAR AL CARRITO ===");
    console.log("Producto ID:", id);
    console.log("Medida:", medidaTexto);
    console.log("Stock disponible:", stock);
    console.log("Cantidad solicitada:", cantidad);
    console.log("Precio:", precio);
    console.log("Descuento:", descuento);

    // Validaciones básicas
    if (cantidad < 1) {
        alert('La cantidad debe ser al menos 1');
        return;
    }

    // Validación de stock
    if (cantidad > stock) {
        alert('No hay suficiente stock disponible. Stock actual: ' + stock + ' unidades');
        return;
    }

    <?php if ($requiere_medidas === 1): ?>
    // Validaciones específicas para productos con medidas
    if (!medidaTexto || medidaTexto.trim() === '') {
        alert('Por favor selecciona una medida.');
        return;
    }
    if (stock <= 0) {
        alert('Lo sentimos, no hay inventario disponible para esta medida.');
        return;
    }
    <?php endif; ?>

    // Preparar datos para enviar
    let formData = new FormData();
    formData.append('id', id);
    formData.append('token', token);
    formData.append('cantidad', cantidad);
    formData.append('precio', precio);
    formData.append('descuento', descuento);
    
    <?php if ($requiere_medidas === 1): ?>
        formData.append('medida', medidaTexto);
    <?php endif; ?>

    console.log("Enviando datos a: clases/carrito.php");

    // Enviar al servidor con mejor manejo de errores
    fetch('clases/carrito.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log("Estado de la respuesta:", response.status);
        console.log("Tipo de contenido:", response.headers.get('content-type'));
        
        // Primero obtener el texto para ver qué está devolviendo
        return response.text().then(text => {
            console.log("Respuesta completa:", text);
            
            try {
                // Intentar parsear como JSON
                return JSON.parse(text);
            } catch (e) {
                console.error("Error parseando JSON:", e);
                console.error("Respuesta recibida (no JSON):", text);
                throw new Error('El servidor devolvió una respuesta no válida: ' + text.substring(0, 100));
            }
        });
    })
    .then(data => {
        console.log("Respuesta parseada:", data);
        if (data.ok) {
            // Actualizar contador del carrito
            if (document.getElementById("num_cart")) {
                document.getElementById("num_cart").textContent = data.numero;
            }
            alert('✓ Producto agregado al carrito correctamente');
        } else {
            alert('✗ Error al agregar el producto: ' + (data.mensaje || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error en la petición:', error);
        alert('Error de conexión: ' + error.message);
    });
}
</script>
<script src="js/carrito.js"></script>
<script>
console.log("=== INFORMACIÓN DEL PRODUCTO ===");
console.log("ID del producto:", <?php echo $id; ?>);
console.log("Token generado:", "<?php echo $token_tmp; ?>");
console.log("Requiere medidas:", <?php echo $requiere_medidas; ?>);
console.log("Variantes disponibles:", <?php echo json_encode($variantes); ?>);
</script>
</body>
</html>