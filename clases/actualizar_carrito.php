<?php
session_start();
require_once '../config/config.php';

$datos = ['ok' => false, 'numero' => 0];

if (isset($_POST['id']) && isset($_POST['cantidad'])) {
    $id = $_POST['id'];
    $cantidad = intval($_POST['cantidad']);

    if ($cantidad < 1) {
        $cantidad = 1;
    }

    // Verificar que el producto exista en el carrito
    if (isset($_SESSION['carrito']['productos'][$id])) {
        $_SESSION['carrito']['productos'][$id] = $cantidad;

        // Recalcular total de productos
        $total_productos = 0;
        foreach ($_SESSION['carrito']['productos'] as $cant) {
            $total_productos += $cant;
        }

        $datos['numero'] = $total_productos;
        $datos['ok'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($datos);
?>