<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ruta segura
$configPath = __DIR__ . '/../config/config.php';

if (!file_exists($configPath)) {
    // Responder solo con JSON, sin HTML
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'numero' => 0]);
    exit;
}

require_once $configPath;

$total = 0;
if (!empty($_SESSION['carrito']['productos']) && is_array($_SESSION['carrito']['productos'])) {
    foreach ($_SESSION['carrito']['productos'] as $item) {
        $total += (int)($item['cantidad'] ?? 0);
    }
}

header('Content-Type: application/json');
echo json_encode(['ok' => true, 'numero' => $total]);
exit;