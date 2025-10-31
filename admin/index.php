<?php
session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
    header('Location: inicio.php');
    exit;
}

require_once 'config/DataBase.php';
require_once 'clases/admin_funciones.php';

$db = new Database();
$con = $db->connect();

$errors = [];

// Crear administrador principal si no existe (descomentar solo la primera vez)
/*
try {
    $sql_check = $con->prepare("SELECT COUNT(*) FROM admin");
    $sql_check->execute();
    if ($sql_check->fetchColumn() == 0) {
        if (crear_admin_principal($con)) {
            $errors[] = "Administrador principal creado. Usuario: admin, Contraseña: admin123";
        }
    }
} catch (Exception $e) {
    // La tabla probablemente no existe aún
}
*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    // Validar campos obligatorios
    if (es_nulo([$usuario, $password])) {
        $errors[] = "Debe llenar todos los campos obligatorios.";
    }

    if (empty($errors)) {
        $resultado = login_admin($usuario, $password, $con);
        if ($resultado === true) {
            header('Location: inicio.php');
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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Panel de Administración - Hidrosistemas">
    <meta name="author" content="">

    <title>Login - Panel de Administración</title>

    <!-- Bootstrap 5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6.4.2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Custom fonts for this template-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .login-body {
            padding: 2rem;
        }
        
        .form-control {
            border: 1px solid #e3e6f0;
            padding: 0.75rem 1rem;
        }
        
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #2980b9, #2471a3);
            transform: translateY(-1px);
        }
        
        .brand-logo {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <div class="brand-logo">
                    <i class="fas fa-tools"></i>
                </div>
                <h1 class="h4">Hidrosistemas</h1>
                <p class="mb-0">Panel de Administración</p>
            </div>
            
            <div class="login-body">
                <?php mostrar_mensajes($errors); ?>

                <form method="post" action="">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="usuario" name="usuario" 
                                   value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>" 
                                   placeholder="Ingresa tu usuario" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Ingresa tu contraseña" required>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-login">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Acceso restringido al personal autorizado
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Efecto de focus en el primer campo
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('usuario').focus();
        });
        
        // Mostrar/ocultar contraseña
        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('form').submit();
            }
        });
    </script>
</body>
</html>