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

if (!defined('KEY_TOKEN')) {
    define('KEY_TOKEN', 'tu_clave_secreta_aqui');
}

$datos = array('ok' => false, 'numero' => 0);

if(isset($_POST['id']) && isset($_POST['token']) && isset($_POST['cantidad'])){
    $id = $_POST['id'];
    $token = $_POST['token'];
    $cantidad = intval($_POST['cantidad']);
    
    if($cantidad < 1) {
        $cantidad = 1;
    }

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
    
    if($token == $token_tmp){
        if(!isset($_SESSION['carrito']['productos'])){
            $_SESSION['carrito']['productos'] = array();
        }

        if(isset($_SESSION['carrito']['productos'][$id])){
            $_SESSION['carrito']['productos'][$id] += $cantidad;
        } else {
            $_SESSION['carrito']['productos'][$id] = $cantidad;
        }

        $total_productos = 0;
        foreach($_SESSION['carrito']['productos'] as $producto_id => $cantidad_producto){
            $total_productos += $cantidad_producto;
        }

        $datos['numero'] = $total_productos;
        $datos['ok'] = true;
    }
}

ob_clean();
header('Content-Type: application/json');
echo json_encode($datos);
exit;