<?php
require_once 'config/DataBase.php';
require_once 'clases/admin_funciones.php';

$db = new Database();
$con = $db->connect();

try {
    if (crear_admin_principal($con)) {
        echo "Administrador principal creado exitosamente.<br>";
        echo "Usuario: admin<br>";
        echo "Contraseña: admin123<br>";
        echo "<strong>¡Cambia la contraseña inmediatamente después del primer login!</strong>";
    } else {
        echo "Error al crear el administrador principal.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>