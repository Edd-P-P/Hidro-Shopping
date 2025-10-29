<?php
session_start();

// Destruir solo las variables de usuario, mantener el carrito
unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_cliente']);

// Redirigir al index
header('Location: index.php');
exit;
?>