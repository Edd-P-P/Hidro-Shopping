<?php
require_once '../config/config.php';

$datos = ['ok' => false, 'numero' => 0];

if (isset($_POST['id']) && isset($_SESSION['carrito']['productos'][$_POST['id']])) {
    unset($_SESSION['carrito']['productos'][$_POST['id']]);

    // Recalcular total
    $total_productos = 0;
    if (isset($_SESSION['carrito']['productos'])) {
        foreach ($_SESSION['carrito']['productos'] as $cantidad) {
            $total_productos += $cantidad;
        }
    }

    $datos['numero'] = $total_productos;
    $datos['ok'] = true;
}

header('Content-Type: application/json');
echo json_encode($datos);
?>