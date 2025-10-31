<?php
// admin/config/config.php

// Configuración de la aplicación
define('RUTA_APP', 'http://localhost/Tienda/Hidro-Shopping');

// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'hidrosistemas_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuración de correo
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USER', 'tu_email@gmail.com');
define('MAIL_PASS', 'tu_password_app');
define('MAIL_PORT', 465);

// Clave de aplicación para cifrado (¡CAMBIAR EN PRODUCCIÓN!)
define('APP_KEY', 'hidrosistemas_2025_secret_key_32chars!!');

// Moneda
define('MONEDA', 'MXN $');
?>