<?php
session_start();
require_once 'config/database.php';

// Recibir los datos de PayPal
$input = json_decode(file_get_contents('php://input'), true);

// Validar que tenemos los datos necesarios
if (isset($input['orderID'], $input['paymentID'], $input['total'])) {
    $db = new Database();
    $con = $db->conectar();

    // Preparar datos para insertar
    $orderID = $input['orderID'];
    $paymentID = $input['paymentID'];
    $total = $input['total'];
    $cart = $input['cart'];
    $payer = $input['payer'];

    // Insertar en la tabla de pedidos
    $sql = "INSERT INTO pedidos (order_id_paypal, payment_id, total, estado, datos_cliente, productos) VALUES (?, ?, ?, 'completado', ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->execute([$orderID, $paymentID, $total, json_encode($payer), json_encode($cart)]);

    // Obtener el ID del pedido insertado
    $orderId = $con->lastInsertId();

    // Vaciar el carrito
    unset($_SESSION['carrito']);

    // Responder con éxito
    echo json_encode(['status' => 'success', 'orderId' => $orderId]);
} else {
    // Responder con error
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
}
?>