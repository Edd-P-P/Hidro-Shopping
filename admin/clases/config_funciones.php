<?php
function cifrar_valor($valor) {
    if (empty($valor)) {
        return '';
    }
    
    $metodo = 'AES-256-CBC';
    $iv_length = openssl_cipher_iv_length($metodo);
    $iv = openssl_random_pseudo_bytes($iv_length);
    
    // Asegurar que la clave tenga 32 caracteres
    $clave = substr(hash('sha256', APP_KEY), 0, 32);
    
    $valor_cifrado = openssl_encrypt($valor, $metodo, $clave, 0, $iv);
    
    if ($valor_cifrado === false) {
        throw new Exception('Error al cifrar el valor');
    }
    
    // Combinar IV y valor cifrado
    return base64_encode($iv . $valor_cifrado);
}

function descifrar_valor($valor_cifrado) {
    if (empty($valor_cifrado)) {
        return '';
    }
    
    $metodo = 'AES-256-CBC';
    $valor_cifrado = base64_decode($valor_cifrado);
    $iv_length = openssl_cipher_iv_length($metodo);
    
    if (strlen($valor_cifrado) < $iv_length) {
        throw new Exception('Valor cifrado inválido');
    }
    
    $iv = substr($valor_cifrado, 0, $iv_length);
    $valor_cifrado = substr($valor_cifrado, $iv_length);
    
    // Asegurar que la clave tenga 32 caracteres
    $clave = substr(hash('sha256', APP_KEY), 0, 32);
    
    $valor_descifrado = openssl_decrypt($valor_cifrado, $metodo, $clave, 0, $iv);
    
    if ($valor_descifrado === false) {
        throw new Exception('Error al descifrar el valor');
    }
    
    return $valor_descifrado;
}

function obtener_configuracion($conexion) {
    $sql = $conexion->prepare("SELECT nombre, valor, tipo, descripcion FROM configuracion");
    $sql->execute();
    $configs = $sql->fetchAll(PDO::FETCH_ASSOC);
    
    $configuraciones = [];
    foreach ($configs as $config) {
        if ($config['tipo'] === 'clave' && !empty($config['valor'])) {
            try {
                $configuraciones[$config['nombre']] = [
                    'valor' => descifrar_valor($config['valor']),
                    'tipo' => $config['tipo'],
                    'descripcion' => $config['descripcion']
                ];
            } catch (Exception $e) {
                $configuraciones[$config['nombre']] = [
                    'valor' => '',
                    'tipo' => $config['tipo'],
                    'descripcion' => $config['descripcion']
                ];
            }
        } else {
            $configuraciones[$config['nombre']] = [
                'valor' => $config['valor'],
                'tipo' => $config['tipo'],
                'descripcion' => $config['descripcion']
            ];
        }
    }
    
    return $configuraciones;
}

function guardar_configuracion($conexion, $nombre, $valor, $tipo, $descripcion = null) {
    $valor_guardar = $valor;
    
    if ($tipo === 'clave') {
        if (!empty($valor) && $valor !== '••••••') {
            $valor_guardar = cifrar_valor($valor);
        } else {
            // Si el valor está vacío o es el placeholder, mantener el valor actual
            $sql_actual = $conexion->prepare("SELECT valor FROM configuracion WHERE nombre = ?");
            $sql_actual->execute([$nombre]);
            $actual = $sql_actual->fetch(PDO::FETCH_ASSOC);
            $valor_guardar = $actual ? $actual['valor'] : '';
        }
    } elseif ($tipo === 'booleano') {
        $valor_guardar = $valor ? '1' : '0';
    }
    
    // Verificar si existe
    $sql = $conexion->prepare("SELECT id FROM configuracion WHERE nombre = ?");
    $sql->execute([$nombre]);
    
    if ($sql->fetch()) {
        // Actualizar
        $sql = $conexion->prepare("UPDATE configuracion SET valor = ?, descripcion = ?, tipo = ? WHERE nombre = ?");
        return $sql->execute([$valor_guardar, $descripcion, $tipo, $nombre]);
    } else {
        // Insertar
        $sql = $conexion->prepare("INSERT INTO configuracion (nombre, valor, descripcion, tipo) VALUES (?, ?, ?, ?)");
        return $sql->execute([$nombre, $valor_guardar, $descripcion, $tipo]);
    }
}

function obtener_valor_config($conexion, $nombre) {
    $sql = $conexion->prepare("SELECT valor, tipo FROM configuracion WHERE nombre = ?");
    $sql->execute([$nombre]);
    $config = $sql->fetch(PDO::FETCH_ASSOC);
    
    if ($config && $config['tipo'] === 'clave' && !empty($config['valor'])) {
        try {
            return descifrar_valor($config['valor']);
        } catch (Exception $e) {
            return '';
        }
    }
    
    return $config ? $config['valor'] : '';
}
?>