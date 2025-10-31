<?php
function es_nulo(array $parametros) {
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function mostrar_mensajes(array $errors) {
    if (count($errors) > 0) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        echo '<ul class="mb-0">';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
}

function login_admin($usuario, $password, $conexion) {
    $sql = $conexion->prepare("SELECT id, nombre, password, activo FROM admin WHERE usuario = ? AND activo = 1 LIMIT 1");
    $sql->execute([$usuario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['nombre'];
            $_SESSION['admin_user'] = $usuario;
            $_SESSION['user_type'] = 'admin';
            return true;
        } else {
            return "El usuario y/o contraseña son incorrectos";
        }
    } else {
        return "El usuario y/o contraseña son incorrectos";
    }
}

// Función para crear el primer administrador (ejecutar una sola vez)
function crear_admin_principal($conexion) {
    $usuario = 'admin';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $nombre = 'Administrador Principal';
    $email = 'admin@hidrosistemas.com';
    
    $sql = $conexion->prepare("INSERT INTO admin (usuario, password, nombre, email) VALUES (?, ?, ?, ?)");
    return $sql->execute([$usuario, $password, $nombre, $email]);
}
?>