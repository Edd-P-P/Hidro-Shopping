<?php
require_once 'config/database.php';
require_once 'clases/cliente_funciones.php';

$db = new Database();
$con = $db->conectar();

// OBTENER CATEGORÍAS PARA EL MENÚ
$sql_todas_categorias = $con->prepare("SELECT id, nombre, slug FROM categorias WHERE activo = 1 ORDER BY id ASC");
$sql_todas_categorias->execute();
$todas_categorias = $sql_todas_categorias->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$mensaje = "";
$mostrar_formulario = false;
$user_id = "";
$token = "";

// Manejar GET (verificación del token)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $user_id = isset($_GET['id']) ? $_GET['id'] : '';
    $token = isset($_GET['token']) ? $_GET['token'] : '';
    
    if (empty($user_id) || empty($token)) {
        header('Location: index.php');
        exit;
    }
    
    if (verifica_token_request($user_id, $token, $con)) {
        $mostrar_formulario = true;
    } else {
        $errors[] = "No se pudo verificar la información";
    }
}

// Manejar POST (cambio de contraseña)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $token = $_POST['token'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    
    // Validar que las contraseñas coincidan
    if (!valida_password($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }
    
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        if (actualiza_password($user_id, $password_hash, $con)) {
            $mensaje = "Contraseña modificada correctamente. <a href='login.php' class='text-decoration-none'>Inicia sesión aquí</a>";
        } else {
            $errors[] = "Error al modificar contraseña. Inténtalo nuevamente";
        }
    } else {
        $mostrar_formulario = true;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Hidrosistemas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .reset-container {
            max-width: 400px;
            margin: 2rem auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .reset-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .reset-body {
            padding: 2rem;
        }
    </style>
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
                    >
                </form>
            </div>
            <div class="header-icons">
                <a href="login.php" class="btn btn-outline-primary">Ingresar</a>
                <a href="checkout.php" class="icon-wrapper">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">0</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="container my-5">
        <div class="reset-container">
            <div class="reset-header">
                <h1><i class="fas fa-lock me-2"></i>Nueva Contraseña</h1>
                <p class="mb-0">Crea una nueva contraseña</p>
            </div>
            
            <div class="reset-body">
                <?php if ($mensaje): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $mensaje; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php mostrar_mensajes($errors); ?>

                <?php if ($mostrar_formulario): ?>
                <form method="post" action="">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Nueva contraseña" required>
                        <label for="password">Nueva Contraseña</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="repassword" name="repassword" 
                               placeholder="Repetir contraseña" required>
                        <label for="repassword">Repetir Contraseña</label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Cambiar Contraseña
                        </button>
                    </div>
                </form>
                <?php endif; ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>