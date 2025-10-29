<?php
require_once 'config/database.php';
require_once 'clases/cliente_funciones.php';

$db = new Database();
$con = $db->conectar();

// OBTENER CATEGORÍAS PARA EL MENÚ
$sql_todas_categorias = $con->prepare("SELECT id, nombre, slug FROM categorias WHERE activo = 1 ORDER BY id ASC");
$sql_todas_categorias->execute();
$todas_categorias = $sql_todas_categorias->fetchAll(PDO::FETCH_ASSOC);

$mensaje = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Validar campos obligatorios
    if (es_nulo([$email])) {
        $errors[] = "Debe ingresar su dirección de correo electrónico.";
    }

    // Validar formato de email
    if (!empty($email) && !es_email($email)) {
        $errors[] = "El formato del email no es válido.";
    }

    if (empty($errors)) {
        // Verificar si el email existe en clientes y está asociado a un usuario
        $sql = $con->prepare("SELECT u.id, c.nombres, c.apellidos 
                             FROM usuarios u 
                             INNER JOIN clientes c ON u.id_cliente = c.id 
                             WHERE c.email = ? AND u.activacion = 1");
        $sql->execute([$email]);
        $usuario = $sql->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Generar token y actualizar en base de datos
            $token = solicita_password($usuario['id'], $con);
            
            if ($token) {
                // Enviar correo de recuperación
                require_once 'clases/mailer.php';
                $mailer = new Mailer();
                
                $url_reset = RUTA_APP . '/reset_password.php?id=' . $usuario['id'] . '&token=' . $token;
                
                $asunto = "Restablecer Contraseña - Tienda Hidrosistemas";
                $cuerpo = "
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                            .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
                            .content { padding: 20px; background: #f9f9f9; }
                            .button { display: inline-block; padding: 12px 24px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; }
                            .footer { text-align: center; padding: 20px; color: #666; }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <div class='header'>
                                <h1>Restablecer Contraseña</h1>
                            </div>
                            <div class='content'>
                                <h2>Hola {$usuario['nombres']} {$usuario['apellidos']},</h2>
                                <p>Has solicitado restablecer tu contraseña. Para continuar, haz clic en el siguiente botón:</p>
                                <p style='text-align: center;'>
                                    <a href='{$url_reset}' class='button'>Restablecer Contraseña</a>
                                </p>
                                <p>Si el botón no funciona, copia y pega esta URL en tu navegador:</p>
                                <p><small>{$url_reset}</small></p>
                                <p><strong>Advertencia:</strong> Si no solicitaste este cambio, ignora este correo.</p>
                                <p>Este enlace expirará después de su uso.</p>
                            </div>
                            <div class='footer'>
                                <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
                            </div>
                        </div>
                    </body>
                    </html>
                ";
                
                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    $mensaje = "Se han enviado las instrucciones para restablecer tu contraseña a {$email}";
                } else {
                    $errors[] = "Error al enviar el correo. Por favor, intenta nuevamente.";
                }
            } else {
                $errors[] = "Error al procesar la solicitud. Inténtalo nuevamente.";
            }
        } else {
            $errors[] = "No existe una cuenta asociada a esta dirección de correo";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Hidrosistemas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .recovery-container {
            max-width: 400px;
            margin: 2rem auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .recovery-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .recovery-body {
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
        <div class="recovery-container">
            <div class="recovery-header">
                <h1><i class="fas fa-key me-2"></i>Recuperar Contraseña</h1>
                <p class="mb-0">Ingresa tu email para continuar</p>
            </div>
            
            <div class="recovery-body">
                <?php if ($mensaje): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $mensaje; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php mostrar_mensajes($errors); ?>

                <form method="post" action="">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                               placeholder="nombre@ejemplo.com" required>
                        <label for="email">Correo Electrónico</label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Continuar
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <p>¿No tienes cuenta? <a href="registro.php" class="text-decoration-none">Regístrate aquí</a></p>
                    </div>
                </form>
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