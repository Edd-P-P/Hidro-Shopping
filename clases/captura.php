<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/cliente_funciones.php';

$db = new Database();
$con = $db->conectar();

// Obtener datos del cliente autenticado
if (!isset($_SESSION['user_cliente'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Cliente no autenticado']);
    exit;
}

$id_cliente = $_SESSION['user_cliente'];

// Obtener email del cliente desde la base de datos
$sql_cliente = $con->prepare("SELECT email FROM clientes WHERE id = ?");
$sql_cliente->execute([$id_cliente]);
$cliente = $sql_cliente->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Cliente no encontrado']);
    exit;
}

$email = $cliente['email'];

// Recibir datos de PayPal
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['orderID'], $input['paymentID'], $input['total'])) {
    $orderID = $input['orderID'];
    $paymentID = $input['paymentID'];
    $total = $input['total'];
    $cart = $input['cart'];

    // CORRECIÓN: Inserción con medio_pago incluido
    $sql = "INSERT INTO pedidos (order_id_paypal, payment_id, total, estado, datos_cliente, productos, id_cliente, email, medio_pago) 
            VALUES (?, ?, ?, 'completado', ?, ?, ?, ?, 'PayPal')";
    
    $stmt = $con->prepare($sql);
    $result = $stmt->execute([
        $orderID, 
        $paymentID, 
        $total, 
        json_encode(['id_cliente' => $id_cliente, 'email' => $email]),
        json_encode($cart),
        $id_cliente,
        $email,
        'PayPal' // CORRECIÓN: medio_pago incluido
    ]);

    if ($result) {
        $orderId = $con->lastInsertId();

        // Enviar correo de confirmación
        require_once __DIR__ . '/mailer.php';
        $mailer = new Mailer();
        
        $asunto = "Confirmación de Pedido #$orderId - Hidrosistemas";
        $cuerpo = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; background: #f9f9f9; }
                    .footer { text-align: center; padding: 20px; color: #666; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>¡Gracias por tu compra!</h1>
                    </div>
                    <div class='content'>
                        <h2>Pedido #$orderId confirmado</h2>
                        <p>Hemos recibido tu pedido correctamente.</p>
                        <p><strong>Total:</strong> " . MONEDA . number_format($total, 2, '.', ',') . "</p>
                        <p><strong>Método de pago:</strong> PayPal</p>
                        <p>Te mantendremos informado sobre el estado de tu pedido.</p>
                    </div>
                    <div class='footer'>
                        <p>Hidrosistemas - Tu tienda de confianza</p>
                    </div>
                </div>
            </body>
            </html>
        ";
        
        $mailer->enviarEmail($email, $asunto, $cuerpo);

        // Vaciar carrito
        unset($_SESSION['carrito']);

        echo json_encode(['status' => 'success', 'orderId' => $orderId]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Error al insertar pedido']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
}
?>