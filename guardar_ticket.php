<?php

require 'conexion.php';
session_start();

// Validar sesión
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit();
}

// Validar datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['titulo']) && !empty($_POST['descripcion'])) {

        $titulo      = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        $usuario_id  = $_SESSION['id'];  
        $tecnico_id  = !empty($_POST['tecnico_id']) ? (int)$_POST['tecnico_id'] : null; // Permitir nulo si no se asigna técnico

        // Insertar ticket en la base de datos
        $sql = "INSERT INTO tickets (titulo, descripcion, usuario_id, tecnico_id, fecha_creacion, estado) VALUES (?, ?, ?, ?, NOW(), 'Pendiente')";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssii", $titulo, $descripcion, $usuario_id, $tecnico_id);

        if ($stmt->execute()) {
            // Redirigir según el rol del usuario
            $rol = strtolower(trim($_SESSION['rol']));

            // Mapeo de roles a archivos
            $dashboards = [
                'administrador' => 'dashboard_admin.php',
                'cliente'       => 'dashboard_cliente.php',
                'tecnico'       => 'dashboard_tecnico.php'
            ];

            $archivo_dashboard = $dashboards[$rol] ?? 'index.php'; // fallback si el rol no existe
            header("Location: $archivo_dashboard");
            exit();

        } else {
            echo "<script>alert(' Error al crear el ticket: " . $stmt->error . "'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Todos los campos son obligatorios'); window.history.back();</script>";
    }
} else {
    header("Location: dashboard_" . strtolower($_SESSION['usuario']) . ".php");
    exit();
}

$conexion->close();
?>
