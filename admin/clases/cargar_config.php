<?php
// admin/config/cargar_config.php

require_once 'DataBase.php';
require_once '../clases/config_funciones.php';

function cargar_configuracion_global() {
    $db = new Database();
    $con = $db->connect();
    
    $configuraciones = obtener_configuracion($con);
    
    // Definir constantes globales
    foreach ($configuraciones as $nombre => $config) {
        $constante_nombre = strtoupper($nombre);
        if (!defined($constante_nombre)) {
            define($constante_nombre, $config['valor']);
        }
    }
    
    return $configuraciones;
}

// Cargar configuración automáticamente si se incluye este archivo
$config_global = cargar_configuracion_global();
?>