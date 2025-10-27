<?php
session_start();
require_once '../config/database.php';
require_once 'cliente_funciones.php';

$db = new Database();
$con = $db->conectar();

$respuesta = ['ok' => false];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'existe_usuario':
            if (isset($_POST['usuario']) && !empty(trim($_POST['usuario']))) {
                $usuario = trim($_POST['usuario']);
                $respuesta['ok'] = usuario_existe($usuario, $con);
            }
            break;
            
        case 'existe_email':
            if (isset($_POST['email']) && !empty(trim($_POST['email']))) {
                $email = trim($_POST['email']);
                $respuesta['ok'] = email_existe($email, $con);
            }
            break;
    }
}

header('Content-Type: application/json');
echo json_encode($respuesta);
?>