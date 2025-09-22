<?php
ob_start();
ob_clean();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$configPath = __DIR__ . '/../config/config.php';
if (!file_exists($configPath)) {
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'error' => 'Config no encontrado']);
    exit;
}
require_once $configPath;

$datos = ['ok' => false, 'numero' => 0];

if (isset($_POST['id']) && isset($_POST['cantidad'])) {
    $id = $_POST['id'];
    $cantidad = intval($_POST['cantidad']);

    // ✅ NUEVA LÓGICA: Si cantidad <= 0, ELIMINAR el producto
    if ($cantidad <= 0) {
        if (isset($_SESSION['carrito']['productos'][$id])) {
            unset($_SESSION['carrito']['productos'][$id]);
        }
    } else {
        // Si cantidad > 0, actualizar normalmente
        if (isset($_SESSION['carrito']['productos'][$id])) {
            $_SESSION['carrito']['productos'][$id] = $cantidad;
        }
    }

    // Recalcular total de productos
    $total_productos = 0;
    if (isset($_SESSION['carrito']['productos']) && is_array($_SESSION['carrito']['productos'])) {
        foreach ($_SESSION['carrito']['productos'] as $cant) {
            $total_productos += $cant;
        }
    }

    $datos['numero'] = $total_productos;
    $datos['ok'] = true;
}

ob_clean();
header('Content-Type: application/json');
echo json_encode($datos);
exit;