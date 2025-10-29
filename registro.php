<?php
session_start();
require_once 'config/config.php';
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
    // Sanitizar entradas
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $dni = trim($_POST['dni']);
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];
    $repetir_password = $_POST['repetir_password'];

    // Validar campos obligatorios
    if (es_nulo([$nombres, $apellidos, $email, $telefono, $dni, $usuario, $password, $repetir_password])) {
        $errors[] = "Todos los campos marcados con * son obligatorios.";
    }

    // Validar formato de email
    if (!empty($email) && !es_email($email)) {
        $errors[] = "El formato del email no es válido.";
    }

    // Validar que las contraseñas coincidan
    if (!valida_password($password, $repetir_password)) {
        $errors[] = "Las contraseñas no coinciden.";
    }

    // Validar que el usuario no exista
    if (!empty($usuario) && usuario_existe($usuario, $con)) {
        $errors[] = "El nombre de usuario ya está registrado.";
    }

    // Validar que el email no exista
    if (!empty($email) && email_existe($email, $con)) {
        $errors[] = "El correo electrónico ya está registrado.";
    }

    // Si no hay errores, proceder con el registro
    if (empty($errors)) {
        try {
            // Cifrar contraseña
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Generar token
            $token = generar_token();
            
            // Registrar cliente
            $datos_cliente = [
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'email' => $email,
                'telefono' => $telefono,
                'dni' => $dni
            ];
            
            $id_cliente = registrar_cliente($datos_cliente, $con);
            
            if ($id_cliente) {
                // Registrar usuario
                $datos_usuario = [
                    'usuario' => $usuario,
                    'password' => $password_hash,
                    'token' => $token,
                    'id_cliente' => $id_cliente
                ];
                
                if (registrar_usuario($datos_usuario, $con)) {
                    // Obtener el ID del usuario recién insertado
                    $sql_usuario = $con->prepare("SELECT id FROM usuarios WHERE usuario = ?");
                    $sql_usuario->execute([$usuario]);
                    $usuario_data = $sql_usuario->fetch(PDO::FETCH_ASSOC);
                    $id_usuario = $usuario_data['id'];
                    
                    // Enviar correo de activación
                    require_once 'clases/mailer.php';
                    $mailer = new Mailer();
                    
                    $url_activacion = RUTA_APP . '/activa_cliente.php?id=' . $id_usuario . '&token=' . $token;
                    
                    $asunto = "Activa tu cuenta - Tienda Hidrosistemas";
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
                                    <h1>¡Bienvenido a Hidrosistemas!</h1>
                                </div>
                                <div class='content'>
                                    <h2>Hola {$nombres} {$apellidos},</h2>
                                    <p>Gracias por registrarte en nuestra tienda. Para activar tu cuenta, haz clic en el siguiente botón:</p>
                                    <p style='text-align: center;'>
                                        <a href='{$url_activacion}' class='button'>Activar Mi Cuenta</a>
                                    </p>
                                    <p>Si el botón no funciona, copia y pega esta URL en tu navegador:</p>
                                    <p><small>{$url_activacion}</small></p>
                                </div>
                                <div class='footer'>
                                    <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
                                </div>
                            </div>
                        </body>
                        </html>
                    ";
                    
                    if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                        $mensaje = "Para terminar el proceso de registro, siga las instrucciones enviadas a {$email}";
                        // Limpiar formulario
                        $_POST = array();
                    } else {
                        $errors[] = "Error al enviar el correo de activación. Por favor, contacta con soporte.";
                    }
                } else {
                    $errors[] = "Error al registrar el usuario.";
                }
            } else {
                $errors[] = "Error al registrar el cliente.";
            }
        } catch (PDOException $e) {
            $errors[] = "Error en el registro: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Hidrosistemas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .bg-transparent {
            background-color: white !important;
        }
        .required::after {
            content: " *";
            color: red;
        }
        .registration-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .registration-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .registration-body {
            padding: 2rem;
        }
        .validation-message {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }
        .is-invalid {
            border-color: #dc3545;
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

    <!-- Contenido Principal -->
    <main class="container my-5">
        <div class="registration-container">
            <div class="registration-header">
                <h1><i class="fas fa-user-plus me-2"></i>Crear Cuenta</h1>
                <p class="mb-0">Regístrate para una mejor experiencia de compra</p>
            </div>
            
            <div class="registration-body">
                <?php if ($mensaje): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $mensaje; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php 
                // ESTA ES LA LÍNEA 203 QUE DA ERROR - AHORA DEBERÍA FUNCIONAR
                mostrar_mensajes($errors); 
                ?>

                <form method="post" action="" id="formRegistro">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombres" class="form-label required">Nombres</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" 
                                       value="<?php echo isset($_POST['nombres']) ? htmlspecialchars($_POST['nombres']) : ''; ?>" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellidos" class="form-label required">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" 
                                       value="<?php echo isset($_POST['apellidos']) ? htmlspecialchars($_POST['apellidos']) : ''; ?>" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label required">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                       required>
                                <span id="valida_email" class="text-danger validation-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label required">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" 
                                       value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dni" class="form-label required">DNI</label>
                                <input type="text" class="form-control" id="dni" name="dni" 
                                       value="<?php echo isset($_POST['dni']) ? htmlspecialchars($_POST['dni']) : ''; ?>" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario" class="form-label required">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" 
                                       value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>" 
                                       required>
                                <span id="valida_usuario" class="text-danger validation-message"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label required">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="repetir_password" class="form-label required">Repetir Contraseña</label>
                                <input type="password" class="form-control" id="repetir_password" name="repetir_password" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Registrar
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <p>¿Ya tienes una cuenta? <a href="login.php" class="text-decoration-none">Inicia sesión aquí</a></p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación básica de contraseñas en el cliente
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const repetirPassword = document.getElementById('repetir_password').value;
            
            if (password !== repetirPassword) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
                return false;
            }
        });

        // Validaciones en tiempo real con AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const usuarioInput = document.getElementById('usuario');
            const emailInput = document.getElementById('email');
            const validaUsuario = document.getElementById('valida_usuario');
            const validaEmail = document.getElementById('valida_email');

            // Validar usuario al perder el foco
            if (usuarioInput) {
                usuarioInput.addEventListener('blur', function() {
                    const usuario = this.value.trim();
                    
                    if (usuario.length > 0) {
                        validarCampo('existe_usuario', 'usuario', usuario, validaUsuario, 'Usuario no disponible');
                    } else {
                        limpiarValidacion(validaUsuario, usuarioInput);
                    }
                });
            }

            // Validar email al perder el foco
            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    const email = this.value.trim();
                    
                    if (email.length > 0) {
                        // Validación básica de formato email
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(email)) {
                            mostrarValidacion(validaEmail, emailInput, 'Formato de email inválido');
                            return;
                        }
                        
                        validarCampo('existe_email', 'email', email, validaEmail, 'Email no disponible');
                    } else {
                        limpiarValidacion(validaEmail, emailInput);
                    }
                });
            }

            function validarCampo(action, campo, valor, elementoMensaje, mensajeError) {
                const formData = new FormData();
                formData.append('action', action);
                formData.append(campo, valor);

                fetch('clases/clienteAjax.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        mostrarValidacion(elementoMensaje, campo === 'usuario' ? usuarioInput : emailInput, mensajeError);
                    } else {
                        limpiarValidacion(elementoMensaje, campo === 'usuario' ? usuarioInput : emailInput);
                    }
                })
                .catch(error => {
                    console.error('Error en la validación:', error);
                });
            }

            function mostrarValidacion(elementoMensaje, input, mensaje) {
                elementoMensaje.textContent = mensaje;
                input.classList.add('is-invalid');
            }

            function limpiarValidacion(elementoMensaje, input) {
                elementoMensaje.textContent = '';
                input.classList.remove('is-invalid');
            }

            // Limpiar validaciones al empezar a escribir nuevamente
            if (usuarioInput) {
                usuarioInput.addEventListener('input', function() {
                    if (this.value.trim().length > 0) {
                        limpiarValidacion(validaUsuario, usuarioInput);
                    }
                });
            }

            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    if (this.value.trim().length > 0) {
                        limpiarValidacion(validaEmail, emailInput);
                    }
                });
            }
        });
    </script>
</body>
</html>