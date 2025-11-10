<?php
session_start();
require_once 'config/database.php';
require_once 'clases/cliente_funciones.php';

$db = new Database();
$con = $db->conectar();

// OBTENER CATEGORÍAS PARA EL MENÚ
$sql_todas_categorias = $con->prepare("SELECT id, nombre, slug FROM categorias WHERE activo = 1 ORDER BY id ASC");
$sql_todas_categorias->execute();
$todas_categorias = $sql_todas_categorias->fetchAll(PDO::FETCH_ASSOC);

$errors = [];

// VERIFICAR PARÁMETRO DE PAGO
$proceso = isset($_GET['pago']) ? 'pago' : 'login';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $proceso = $_POST['proceso']; // Obtener el proceso del formulario

    // Validar campos obligatorios
    if (es_nulo([$usuario, $password])) {
        $errors[] = "Debe llenar todos los campos obligatorios.";
    }

    if (empty($errors)) {
        $resultado = login($usuario, $password, $con);
        if ($resultado === true) {
            // Redirigir según el proceso
            if ($proceso === 'pago') {
                header('Location: checkout.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $errors[] = $resultado;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Hidrosistemas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="Imagenes/h.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .bg-transparent {
            background-color: white !important;
        }
        .login-container {
            max-width: 400px;
            margin: 2rem auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-body {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <!-- Contenido Principal -->
    <main class="container my-5">
        <div class="login-container">
            <div class="login-header">
                <h1><i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión</h1>
                <p class="mb-0"><?php echo $proceso === 'pago' ? 'Inicia sesión para continuar con tu compra' : 'Accede a tu cuenta'; ?></p>
            </div>
            
            <div class="login-body">
                <?php mostrar_mensajes($errors); ?>

                <form method="post" action="">
                    <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="usuario" name="usuario" 
                               value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>" 
                               placeholder="Usuario" required>
                        <label for="usuario">Usuario</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Contraseña" required>
                        <label for="password">Contraseña</label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Ingresar
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="recupera.php" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
            // Script para el menú retráctil de categorías en ESCRITORIO
    document.addEventListener('DOMContentLoaded', function() {
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>