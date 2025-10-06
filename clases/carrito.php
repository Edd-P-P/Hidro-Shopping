<?php
// clases/carrito.php

// Iniciar sesión al principio
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Headers para JSON - DEBE SER LO PRIMERO
header('Content-Type: application/json');

// Respuesta por defecto
$response = ['ok' => false, 'numero' => 0, 'mensaje' => 'Error desconocido'];

try {
    // Verificar método
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Verificar datos requeridos
    if (!isset($_POST['id']) || !isset($_POST['token']) || !isset($_POST['cantidad'])) {
        throw new Exception('Faltan datos requeridos');
    }

    // Incluir configuraciones
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../config/database.php';

    // Validar que KEY_TOKEN esté definido
    if (!defined('KEY_TOKEN')) {
        throw new Exception('Configuración incompleta');
    }

    // Obtener datos
    $id = (int)$_POST['id'];
    $token = $_POST['token'];
    $cantidad = max(1, (int)$_POST['cantidad']);
    $medida = $_POST['medida'] ?? '';
    $precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0;
    $descuento = isset($_POST['descuento']) ? (float)$_POST['descuento'] : 0;

    // Validar token
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
    if (!hash_equals($token, $token_tmp)) {
        throw new Exception('Token inválido');
    }

    // Conectar a la base de datos
    $db = new Database();
    $con = $db->conectar();

    // Obtener producto
    $stmt = $con->prepare("SELECT id, nombre, precio, descuento, stock, requiere_medidas FROM productos WHERE id = ? AND activo = 1");
    $stmt->execute([$id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        throw new Exception('Producto no disponible');
    }

    $requiere_medidas = (int)$producto['requiere_medidas'];
    
    // Inicializar variables
    $precio_final = (float)$producto['precio'];
    $descuento_final = (float)$producto['descuento'];
    $stock_disponible = (int)$producto['stock'];

    // Si requiere medidas
    if ($requiere_medidas === 1) {
        if (empty($medida)) {
            throw new Exception('Por favor selecciona una medida');
        }

        // Buscar la variante específica
        $stmt = $con->prepare("SELECT precio_m, descuento_m, stock_m FROM productos_medidas WHERE producto_id = ? AND medida_id = ?");
        $stmt->execute([$id, $medida]);
        $variante = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$variante) {
            throw new Exception('Medida no disponible');
        }

        $stock_disponible = (int)$variante['stock_m'];
        $precio_final = (float)$variante['precio_m'];
        $descuento_final = (float)$variante['descuento_m'];
    } else {
        // Usar valores del formulario para productos sin medidas
        $precio_final = $precio;
        $descuento_final = $descuento;
    }

    // Validar stock
    if ($stock_disponible < $cantidad) {
        throw new Exception("Stock insuficiente. Disponible: $stock_disponible unidades");
    }

    // Calcular precio con descuento
    $precio_con_descuento = $descuento_final > 0 
        ? $precio_final - ($precio_final * $descuento_final / 100)
        : $precio_final;

    // Clave única para el carrito
    $clave = $requiere_medidas ? "$id-$medida" : (string)$id;

    // Inicializar carrito si no existe - CORREGIDO: estructura consistente
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = ['productos' => []];
    } elseif (!isset($_SESSION['carrito']['productos'])) {
        $_SESSION['carrito']['productos'] = [];
    }

    // Agregar o actualizar producto - CORREGIDO: estructura consistente
    if (isset($_SESSION['carrito']['productos'][$clave])) {
        $nueva_cantidad = $_SESSION['carrito']['productos'][$clave]['cantidad'] + $cantidad;
        if ($nueva_cantidad > $stock_disponible) {
            throw new Exception("No puedes agregar más de $stock_disponible unidades");
        }
        $_SESSION['carrito']['productos'][$clave]['cantidad'] = $nueva_cantidad;
    } else {
        $_SESSION['carrito']['productos'][$clave] = [
            'id' => $id,
            'cantidad' => $cantidad,
            'precio' => $precio_con_descuento,
            'medida' => $medida,
            'requiere_medidas' => $requiere_medidas
        ];
    }

    // Calcular total de productos en carrito - CORREGIDO: estructura consistente
    $total_productos = 0;
    foreach ($_SESSION['carrito']['productos'] as $item) {
        // CORRECIÓN: Verificar que existe la clave 'cantidad'
        if (isset($item['cantidad'])) {
            $total_productos += $item['cantidad'];
        }
    }

    // Respuesta exitosa
    $response = [
        'ok' => true, 
        'numero' => $total_productos, 
        'mensaje' => 'Producto agregado al carrito'
    ];

} catch (Exception $e) {
    $response = ['ok' => false, 'numero' => 0, 'mensaje' => $e->getMessage()];
}

// Enviar respuesta JSON limpia
echo json_encode($response);
exit;