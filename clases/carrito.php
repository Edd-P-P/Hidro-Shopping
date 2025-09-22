<?php

require '../config/config.php';

// Definir KEY_TOKEN si no está definido
if(!defined('KEY_TOKEN')) {
    define('KEY_TOKEN', 'tu_clave_secreta_aqui'); // Cambia por tu clave real
}

$datos = array('ok' => false, 'numero' => 0);

// Verificar que se recibieron los datos POST
if(isset($_POST['id']) && isset($_POST['token']) && isset($_POST['cantidad'])){
    
    $id = $_POST['id'];
    $token = $_POST['token'];
    $cantidad = intval($_POST['cantidad']); // Convertir a entero

    // Validar que la cantidad sea válida
    if($cantidad < 1) {
        $cantidad = 1;
    }

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
    
    if($token == $token_tmp){
        
        // Inicializar carrito si no existe
        if(!isset($_SESSION['carrito']['productos'])){
            $_SESSION['carrito']['productos'] = array();
        }

        // Lógica corregida: si existe, sumar la cantidad; si no, establecer la cantidad
        if(isset($_SESSION['carrito']['productos'][$id])){
            $_SESSION['carrito']['productos'][$id] += $cantidad;
        } else {
            $_SESSION['carrito']['productos'][$id] = $cantidad;
        }

        // Calcular el total de productos (sumando todas las cantidades)
        $total_productos = 0;
        foreach($_SESSION['carrito']['productos'] as $producto_id => $cantidad_producto){
            $total_productos += $cantidad_producto;
        }

        $datos['numero'] = $total_productos;
        $datos['ok'] = true;

    } else {
        $datos['ok'] = false;
    }
    
} else {
    $datos['ok'] = false;
}

echo json_encode($datos);
?>

<!-- cambio -->