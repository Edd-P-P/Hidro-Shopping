<?php
function generar_token() {
    return md5(uniqid(mt_rand(), false));
}

function registrar_cliente($datos, $conexion) {
    $sql = "INSERT INTO clientes (nombres, apellidos, email, telefono, dni, estatus, fecha_alta) 
            VALUES (:nombres, :apellidos, :email, :telefono, :dni, 1, NOW())";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombres', $datos['nombres']);
    $stmt->bindParam(':apellidos', $datos['apellidos']);
    $stmt->bindParam(':email', $datos['email']);
    $stmt->bindParam(':telefono', $datos['telefono']);
    $stmt->bindParam(':dni', $datos['dni']);
    
    if ($stmt->execute()) {
        return $conexion->lastInsertId();
    } else {
        return false;
    }
}

function registrar_usuario($datos, $conexion) {
    $sql = "INSERT INTO usuarios (usuario, password, activacion, token, id_cliente) 
            VALUES (:usuario, :password, 0, :token, :id_cliente)";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':usuario', $datos['usuario']);
    $stmt->bindParam(':password', $datos['password']);
    $stmt->bindParam(':token', $datos['token']);
    $stmt->bindParam(':id_cliente', $datos['id_cliente']);
    
    return $stmt->execute();
}

// --- NUEVAS FUNCIONES DE VALIDACIÓN ---

function es_nulo(array $parametros) {
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function es_email(string $email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function valida_password(string $password, string $repassword) {
    if (strcmp($password, $repassword) === 0) {
        return true;
    }
    return false;
}

function usuario_existe(string $usuario, PDO $conexion) {
    $sql = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ? LIMIT 1");
    $sql->execute([$usuario]);
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function email_existe(string $email, PDO $conexion) {
    $sql = $conexion->prepare("SELECT id FROM clientes WHERE email = ? LIMIT 1");
    $sql->execute([$email]);
    if ($sql->fetchColumn() > 0) {
        return true;
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
function valida_token($id_usuario, $token, $conexion) {
    $sql = $conexion->prepare("SELECT id FROM usuarios WHERE id = ? AND token = ? AND activacion = 0 LIMIT 1");
    $sql->execute([$id_usuario, $token]);
    return $sql->fetch() !== false;
}

function activar_usuario($id_usuario, $conexion) {
    $sql = $conexion->prepare("UPDATE usuarios SET activacion = 1, token = NULL WHERE id = ?");
    return $sql->execute([$id_usuario]);
}
function es_activo($usuario, $conexion) {
    $sql = $conexion->prepare("SELECT activacion FROM usuarios WHERE usuario = ? LIMIT 1");
    $sql->execute([$usuario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return $row['activacion'] == 1;
    }
    return false;
}

function login($usuario, $password, $conexion) {
    $sql = $conexion->prepare("SELECT id, password FROM usuarios WHERE usuario = ? LIMIT 1");
    $sql->execute([$usuario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if (es_activo($usuario, $conexion)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $usuario;
                header('Location: index.php');
                exit();
            } else {
                return "El usuario y/o contraseña son incorrectos";
            }
        } else {
            return "El usuario no ha sido activado";
        }
    } else {
        return "El usuario y/o contraseña son incorrectos";
    }
}
?>