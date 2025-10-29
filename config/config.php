<?php
define('RUTA_APP', 'http://localhost/Tienda/Hidro-Shopping'); // Link de mi dominio
define('DB_HOST', 'localhost');
define('DB_NAME', 'hidro-online2');
define('DB_USER', 'root');
define('DB_PASS', '');
define("KEY_TOKEN", "H1dr0.Sh0pp1ng2O2A");

// En desarrollo: muestra errores
error_reporting(E_ALL);
/* Configuracion del correo */
ini_set('display_errors', 1);
define('MAIL_HOST', 'smtp.gmail.com'); // o tu servidor SMTP
define('MAIL_USER', 'ea439921@gmail.com'); // mi correo
define('MAIL_PASS', 'tu_contraseña_de_aplicacion');
define('MAIL_PORT', 465);
/* Definición de la moneda */
define("MONEDA", "MXN $");