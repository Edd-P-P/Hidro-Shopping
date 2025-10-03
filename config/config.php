<?php
define("KEY_TOKEN", "H1dr0.Sh0pp1ng2O2A");
define("MONEDA", "$");
// En desarrollo: muestra errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// En producción: oculta errores
// error_reporting(0);
// ini_set('display_errors', 0);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}