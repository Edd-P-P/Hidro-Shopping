<?php
session_start();

// Destruir solo las variables de sesión del administrador
unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_user'], $_SESSION['user_type']);

// Redirigir al login
header('Location: index.php');
exit;
?>