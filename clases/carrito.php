<?php
// ¡NADA antes de <?php — ni espacios, ni saltos de línea!

// Limpiar cualquier output previo
ob_start();
ob_clean();

// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir ruta al config de forma robusta
$configPath = __DIR__ . '/../config/config.php';
if (!file_exists($configPath)) {
    // Si no existe, devolver error en JSON y salir
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'numero' => 0, 'error' => 'Config no encontrado']);
    exit;
}
require_once $configPath;

// Definir KEY_TOKEN si no existe
if (!defined('KEY_TOKEN')) {
    define('KEY_TOKEN', 'fallback_token_2025');
}

$datos = ['ok' => false, 'numero' => 0];

// Verificar que se recibieron los datos POST
if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['cantidad'])) {
   
    $id = $_POST['id'];
    $token = $_POST['token'];
    $cantidad = intval($_POST['cantidad']);
   
    if ($cantidad < 1) {
        $cantidad = 1;
    }

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
   
    if ($token == $token_tmp) {
       
        // Inicializar carrito si no existe
        if (!isset($_SESSION['carrito']['productos'])) {
            $_SESSION['carrito']['productos'] = [];
        }

        // Sumar cantidad si ya existe, o asignar si es nuevo
        if (isset($_SESSION['carrito']['productos'][$id])) {
            $_SESSION['carrito']['productos'][$id] += $cantidad;
        } else {
            $_SESSION['carrito']['productos'][$id] = $cantidad;
        }

        // Calcular total
        $total_productos = 0;
        foreach ($_SESSION['carrito']['productos'] as $cant) {
            $total_productos += $cant;
        }

        $datos['numero'] = $total_productos;
        $datos['ok'] = true;

    }
}

// ¡LIMPIAR TODO ANTES DE ENVIAR!
ob_clean();

// Enviar solo JSON
header('Content-Type: application/json');
echo json_encode($datos);
exit;