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

if (isset($_POST['id']) && isset($_SESSION['carrito']['productos'][$_POST['id']])) {
    unset($_SESSION['carrito']['productos'][$_POST['id']]);

    $total_productos = 0;
    if (isset($_SESSION['carrito']['productos'])) {
        foreach ($_SESSION['carrito']['productos'] as $cantidad) {
            $total_productos += $cantidad;
        }
    }

    $datos['numero'] = $total_productos;
    $datos['ok'] = true;
}

ob_clean();
header('Content-Type: application/json');
echo json_encode($datos);
exit;