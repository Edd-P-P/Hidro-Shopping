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

if (!defined('KEY_TOKEN')) {
    define('KEY_TOKEN', 'tu_clave_secreta_aqui');
}

$datos = ['ok' => false, 'numero' => 0, 'mensaje' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode($datos);
    exit;
}

if (!isset($_POST['id']) || !isset($_POST['token']) || !isset($_POST['cantidad'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Faltan datos']);
    exit;
}

$id = (int)$_POST['id'];
$token = $_POST['token'];
$cantidad = max(1, (int)$_POST['cantidad']);
$medida = $_POST['medida'] ?? null;

$token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
if (!hash_equals($token, $token_tmp)) {
    echo json_encode(['ok' => false, 'mensaje' => 'Token inválido']);
    exit;
}

try {
    $db = new Database();
    $con = $db->conectar();

    // Obtener producto base
    $stmt = $con->prepare("SELECT id, nombre, precio, descuento, stock, requiere_medidas FROM productos WHERE id = ? AND activo = 1");
    $stmt->execute([$id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        echo json_encode(['ok' => false, 'mensaje' => 'Producto no disponible']);
        exit;
    }

    $requiere_medidas = (int)$producto['requiere_medidas'];
    $precio_usado = (float)$producto['precio'];
    $descuento_usado = (float)$producto['descuento'];
    $stock_disponible = (int)$producto['stock']; // ← Para productos sin variantes

    // Si requiere medidas, validar variante
    if ($requiere_medidas === 1) {
        if (!$medida) {
            echo json_encode(['ok' => false, 'mensaje' => 'Selecciona una medida']);
            exit;
        }

        // Me confundía y le puse stock_m en lugar de stock
        $stmt = $con->prepare("
            SELECT pm.precio_m, pm.descuento_m, pm.stock_m AS stock, mc.medida
            FROM productos_medidas pm
            JOIN medidas_categoria mc ON pm.medida_id = mc.id
            WHERE pm.producto_id = ? AND mc.medida = ?
        ");
        $stmt->execute([$id, $medida]);
        $variante = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$variante) {
            echo json_encode(['ok' => false, 'mensaje' => 'Variante no disponible']);
            exit;
        }

        $stock_disponible = (int)$variante['stock'];
        $precio_usado = (float)$variante['precio_m'];
        $descuento_usado = (float)$variante['descuento_m'];
    }

    // verificar stock si sigue siendo menor que cantidad
    if ($stock_disponible < $cantidad) {
        echo json_encode(['ok' => false, 'mensaje' => "Stock insuficiente. Disponible: {$stock_disponible}"]);
        exit;
    }

    // Clave única
    $clave = $requiere_medidas ? "{$id}-{$medida}" : $id;

    if (!isset($_SESSION['carrito']['productos'])) {
        $_SESSION['carrito']['productos'] = [];
    }

    $_SESSION['carrito']['productos'][$clave] = [
        'id' => $id,
        'cantidad' => $cantidad,
        'precio' => $precio_usado,
        'descuento' => $descuento_usado,
        'medida' => $requiere_medidas ? $medida : null,
        'requiere_medidas' => $requiere_medidas
    ];

    // Calcular total
    $total = 0;
    foreach ($_SESSION['carrito']['productos'] as $item) {
        $total += $item['cantidad'];
    }

    echo json_encode(['ok' => true, 'numero' => $total]);

} catch (Exception $e) {
    echo json_encode(['ok' => false, 'mensaje' => 'Error al procesar']);
}
exit;