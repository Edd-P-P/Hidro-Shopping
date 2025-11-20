<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once '../config/database.php';

$db = new Database();
$con = $db->conectar();

header('Content-Type: application/json');

// Verificar si es una compra inmediata
if (!isset($_POST['buynow']) || $_POST['buynow'] !== 'true') {
    echo json_encode(['ok' => false, 'mensaje' => 'Solicitud inválida']);
    exit;
}

// Verificar autenticación del usuario
if (!isset($_SESSION['user_cliente'])) {
    echo json_encode([
        'ok' => false, 
        'mensaje' => 'Por favor inicie sesión primero',
        'redirect' => 'login.php?buynow=1'
    ]);
    exit;
}

// Validar token
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$token = isset($_POST['token']) ? $_POST['token'] : '';

if ($id <= 0 || empty($token)) {
    echo json_encode(['ok' => false, 'mensaje' => 'Datos de producto inválidos']);
    exit;
}

$token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
if (!hash_equals($token, $token_tmp)) {
    echo json_encode(['ok' => false, 'mensaje' => 'Token inválido']);
    exit;
}

// Obtener información del producto
$stmt = $con->prepare("SELECT nombre, requiere_medidas FROM productos WHERE id = ? AND activo = 1");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    echo json_encode(['ok' => false, 'mensaje' => 'Producto no encontrado']);
    exit;
}

// Validar datos recibidos
$cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;
$precio = (float)$_POST['precio'];
$descuento = (float)$_POST['descuento'];
$medida = isset($_POST['medida']) ? $_POST['medida'] : null;
$requiere_medidas = (int)$producto['requiere_medidas'];

// Validaciones adicionales
if ($cantidad < 1) {
    echo json_encode(['ok' => false, 'mensaje' => 'La cantidad debe ser al menos 1']);
    exit;
}

// Validar stock
if ($requiere_medidas === 1) {
    if (empty($medida)) {
        echo json_encode(['ok' => false, 'mensaje' => 'Debe seleccionar una medida']);
        exit;
    }
    
    // Verificar stock en la variante específica
    $stmtStock = $con->prepare("SELECT stock_m FROM productos_medidas WHERE producto_id = ? AND medida_id = ?");
    $stmtStock->execute([$id, $medida]);
    $stockData = $stmtStock->fetch(PDO::FETCH_ASSOC);
    
    if (!$stockData || $stockData['stock_m'] < $cantidad) {
        echo json_encode(['ok' => false, 'mensaje' => 'No hay suficiente stock disponible para la medida seleccionada']);
        exit;
    }
} else {
    // Verificar stock del producto base
    $stmtStock = $con->prepare("SELECT stock FROM productos WHERE id = ?");
    $stmtStock->execute([$id]);
    $stockData = $stmtStock->fetch(PDO::FETCH_ASSOC);
    
    if ($stockData['stock'] < $cantidad) {
        echo json_encode(['ok' => false, 'mensaje' => 'No hay suficiente stock disponible']);
        exit;
    }
}

// LIMPIAR CARRITO ACTUAL Y AGREGAR SOLO ESTE PRODUCTO
$_SESSION['carrito'] = [
    'productos' => []
];

// Generar clave única para el producto en el carrito
$clave = $id . ($requiere_medidas ? '-' . urlencode($medida) : '');

// Agregar producto al carrito
$_SESSION['carrito']['productos'][$clave] = [
    'id' => $id,
    'cantidad' => $cantidad,
    'precio' => $precio,
    'descuento' => $descuento,
    'medida' => $medida,
    'requiere_medidas' => $requiere_medidas
];

echo json_encode([
    'ok' => true,
    'mensaje' => 'Producto listo para compra inmediata',
    'redirect' => 'pago.php'
]);
exit;
?>