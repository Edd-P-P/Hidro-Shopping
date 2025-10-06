<?php
// clases/actualizar_carrito.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Headers para JSON
header('Content-Type: application/json');

$response = ['ok' => false, 'numero' => 0, 'mensaje' => ''];

if (!isset($_POST['clave']) || !isset($_POST['cantidad'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Faltan datos']);
    exit;
}

$clave = $_POST['clave'];
$cantidad = (int)$_POST['cantidad'];

try {
    // Verificar que el carrito existe y tiene la estructura correcta
    if (!isset($_SESSION['carrito']['productos'][$clave])) {
        throw new Exception('Producto no encontrado en el carrito');
    }

    $item = $_SESSION['carrito']['productos'][$clave];
    $id = $item['id'];
    $medida = $item['medida'] ?? null;
    $requiere_medidas = $item['requiere_medidas'] ?? 0;

    if ($cantidad <= 0) {
        // Eliminar producto
        unset($_SESSION['carrito']['productos'][$clave]);
    } else {
        // Validar stock en BD
        require_once __DIR__ . '/../config/database.php';
        $db = new Database();
        $con = $db->conectar();

        if ($requiere_medidas) {
            $stmt = $con->prepare("SELECT stock_m FROM productos_medidas WHERE producto_id = ? AND medida_id = ?");
            $stmt->execute([$id, $medida]);
            $variante = $stmt->fetch(PDO::FETCH_ASSOC);
            $stock_actual = $variante ? (int)$variante['stock_m'] : 0;
        } else {
            $stmt = $con->prepare("SELECT stock FROM productos WHERE id = ? AND activo = 1");
            $stmt->execute([$id]);
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            $stock_actual = $prod ? (int)$prod['stock'] : 0;
        }

        if ($stock_actual < $cantidad) {
            throw new Exception("Stock insuficiente. Disponible: {$stock_actual}");
        }

        $_SESSION['carrito']['productos'][$clave]['cantidad'] = $cantidad;
    }

    // Calcular total - CORREGIDO: verificar existencia de 'cantidad'
    $total = 0;
    if (isset($_SESSION['carrito']['productos'])) {
        foreach ($_SESSION['carrito']['productos'] as $item) {
            if (isset($item['cantidad'])) {
                $total += $item['cantidad'];
            }
        }
    }

    $response = ['ok' => true, 'numero' => $total];

} catch (Exception $e) {
    $response = ['ok' => false, 'mensaje' => $e->getMessage()];
}

echo json_encode($response);
exit;