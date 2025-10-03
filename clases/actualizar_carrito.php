<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$configPath = __DIR__ . '/../config/config.php';
$databasePath = __DIR__ . '/../config/database.php';

if (!file_exists($configPath) || !file_exists($databasePath)) {
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'mensaje' => 'Error interno']);
    exit;
}

require_once $configPath;
require_once $databasePath;

$datos = ['ok' => false, 'numero' => 0, 'mensaje' => ''];

if (!isset($_POST['clave']) || !isset($_POST['cantidad'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Faltan datos']);
    exit;
}

$clave = $_POST['clave'];
$cantidad = (int)$_POST['cantidad'];

if ($cantidad <= 0) {
    unset($_SESSION['carrito']['productos'][$clave]);
} else {
    if (!isset($_SESSION['carrito']['productos'][$clave])) {
        echo json_encode(['ok' => false, 'mensaje' => 'Producto no encontrado']);
        exit;
    }

    $item = $_SESSION['carrito']['productos'][$clave];
    $id = $item['id'];
    $medida = $item['medida'] ?? null;
    $requiere_medidas = $item['requiere_medidas'] ?? 0;

    // Validar stock en BD
    $db = new Database();
    $con = $db->conectar();

    if ($requiere_medidas) {
        $stmt = $con->prepare("
            SELECT pm.stock_m AS stock
            FROM productos_medidas pm
            JOIN medidas_categoria mc ON pm.medida_id = mc.id
            WHERE pm.producto_id = ? AND mc.medida = ?
        ");
        $stmt->execute([$id, $medida]);
        $variante = $stmt->fetch(PDO::FETCH_ASSOC);
        $stock_actual = $variante ? (int)$variante['stock'] : 0;
    } else {
        $stmt = $con->prepare("SELECT stock FROM productos WHERE id = ? AND activo = 1");
        $stmt->execute([$id]);
        $prod = $stmt->fetch(PDO::FETCH_ASSOC);
        $stock_actual = $prod ? (int)$prod['stock'] : 0;
    }

    if ($stock_actual < $cantidad) {
        echo json_encode(['ok' => false, 'mensaje' => "Stock insuficiente. Disponible: {$stock_actual}"]);
        exit;
    }

    $_SESSION['carrito']['productos'][$clave]['cantidad'] = $cantidad;
}

$total = 0;
foreach ($_SESSION['carrito']['productos'] ?? [] as $item) {
    $total += $item['cantidad'];
}

echo json_encode(['ok' => true, 'numero' => $total]);
exit;