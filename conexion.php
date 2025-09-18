<?php
$servidor = "localhost";
$usuario = "root"; 
$clave = "";       
$bd = "proyecto_nm";

$conexion = new mysqli($servidor, $usuario, $clave, $bd);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
