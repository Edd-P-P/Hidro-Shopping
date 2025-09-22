<?php 
require_once 'config/config.php';
require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id']: '';
$token = isset($_GET['token']) ? $_GET['token']: '';

if($id == '' || $token == ''){
    echo 'Error al procesar la petición';
    exit;
}else{
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
    if($token == $token_tmp){
        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
        $sql->execute([$id]);
        if($sql->fetchColumn() > 0){
            $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            
            // RUTA DE IMAGEN CORREGIDA
            $dir_imagen = "imagenes/productos/";
            $rutaImg = $dir_imagen . $id . ".jpeg";
            
            // Verificar si la imagen existe, si no usar default
            if (!file_exists($rutaImg)) {
                $rutaImg = $dir_imagen . "default.png";
            }
        }else{
            echo 'Error al procesar la petición';
            exit;
        }
    }else{
        echo 'Error al procesar la petición';
        exit;
    }
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HidroBuy </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar productos.">
            </div>
            
            <div class="header-icons">
                <a href="#"><i class="fas fa-user"></i></a>
                <a href="#" class="icon-wrapper">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="num_cart" class="badge bg-secondary"></span>
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
                <h2 class="product-title"><?php echo $nombre;?></h2>
                <?php if($descuento > 0) { ?>
                    <p><del><?php echo MONEDA . number_format($precio, 2, '.');?></del></p>
                    <h2 class="product-price"><?php echo MONEDA . number_format($precio_desc, 2, '.');?>
                    <small class="text-succes"><?php echo $descuento ?>% descuento </small>
                    </h2>
                    <?php } else { ?>
                    <h2 class="product-price"><?php echo MONEDA . number_format($precio, 2, '.');?></h2>
                    <?php } ?>
                <p class="product-description">
                    <?php echo $descripcion;?>
                </p>
                
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
                
                <!-- Características del producto -->
                <div class="features-prod">
                    <h4 class="section-title">Posible tabla</h4>
                    <div class="feature-prod-item">
                        <div class="feature-prod-icon"><i class="fas fa-check-circle"></i></div>
                        <div>Materiales de alta calidad</div>
                    </div>
                    <div class="feature-prod-item">
                        <div class="feature-prod-icon"><i class="fas fa-check-circle"></i></div>
                        <div>Garantía de 2 años</div>
                    </div>
                    <div class="feature-prod-item">
                        <div class="feature-prod-icon"><i class="fas fa-check-circle"></i></div>
                        <div>Envio gratuito</div>
                    </div>
                    <div class="feature-prod-item">
                        <div class="feature-prod-icon"><i class="fas fa-check-circle"></i></div>
                        <div>Devoluciones sin problemas</div>
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
    <script src="app.js"></script>
    <script>
        /* Funcionalidad de los botones para aumentar cantidad de productos */
                document.addEventListener('DOMContentLoaded', function() {
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
        });

        /* Funcionamiento carrito */
/* Funcionamiento carrito */
    function addProducto(id, token){
        // Obtener la cantidad del input
        let cantidad = parseInt(document.getElementById('quantity').value) || 1;
        
        let url = 'clases/carrito.php'
        let formData = new FormData()
        formData.append('id', id)
        formData.append('token', token)
        formData.append('cantidad', cantidad) // ¡Agregar la cantidad al FormData!

        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json())
        .then(data => {
            if(data.ok){
                let elemento = document.getElementById("num_cart")
                elemento.innerHTML = data.numero
                // Opcional: mostrar mensaje de éxito
                alert('Producto agregado al carrito');
            } else {
                alert('Error al agregar el producto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    </script>
</body>
</html>