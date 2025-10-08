<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servidor = "sql306.infinityfree.com";
$usuario = "if0_40113229"; 
$clave = "bucaramangaSAN1";       
$bd = "if0_40113229_proyecto_nm";

$conexion = new mysqli($servidor, $usuario, $clave, $bd);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
