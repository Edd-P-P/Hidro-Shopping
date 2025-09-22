<?php
// INICIO: NADA DEBE ESTAR ANTES DE <?php — ni espacios, ni saltos de línea

// Limpiar cualquier output previo (por si acaso)
ob_start();
ob_clean();

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir ruta base de forma robusta
$root = $_SERVER['DOCUMENT_ROOT'];
$configPath = $root . '/config/config.php';

// Verificar si el archivo existe
if (!file_exists($configPath)) {
    // Intentar ruta relativa alternativa
    $configPath = __DIR__ . '/../config/config.php';
    if (!file_exists($configPath)) {
        // Si aún no existe, devolver JSON de error y salir
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'numero' => 0, 'error' => 'Config no encontrado']);
        exit;
    }
}

// Incluir config
require_once $configPath;

// Definir KEY_TOKEN si no existe
if (!defined('KEY_TOKEN')) {
    define('KEY_TOKEN', 'fallback_token_2025');
}

// Inicializar respuesta
$datos = ['ok' => false, 'numero' => 0];

// Verificar carrito en sesión
if (isset($_SESSION['carrito']['productos']) && is_array($_SESSION['carrito']['productos'])) {
    $total = 0;
    foreach ($_SESSION['carrito']['productos'] as $cantidad) {
        $total += $cantidad;
    }
    $datos['numero'] = $total;
    $datos['ok'] = true;
}

// Limpiar buffer de salida (¡CRUCIAL!)
ob_clean();

// Enviar solo JSON
header('Content-Type: application/json');
echo json_encode($datos);
exit;