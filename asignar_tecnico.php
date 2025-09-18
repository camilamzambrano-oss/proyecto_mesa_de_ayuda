<?php
session_start();
require 'conexion.php';

// Asegurarse de que el usuario está logueado y es cliente
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'Cliente') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $tecnico_id = $_POST['tecnico_id'] !== '' ? $_POST['tecnico_id'] : null;

    // Seguridad básica: asegúrate de que el ticket pertenece al usuario
    $usuario_id = $_SESSION['id'];
    $check_ticket = mysqli_query($conexion, "SELECT id FROM tickets WHERE id = '$ticket_id' AND usuario_id = '$usuario_id'");

    if (mysqli_num_rows($check_ticket) > 0) {
        if ($tecnico_id) {
            $stmt = mysqli_prepare($conexion, "UPDATE tickets SET tecnico_id = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "ii", $tecnico_id, $ticket_id);
        } else {
            // Asignar NULL si se elige "Sin asignar"
            $stmt = mysqli_prepare($conexion, "UPDATE tickets SET tecnico_id = NULL WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $ticket_id);
        }

        if (mysqli_stmt_execute($stmt)) {
            // Redirigir de vuelta al dashboard
            header("Location: dashboard_cliente.php");
            exit();
        } else {
            echo "Error al actualizar el técnico.";
        }
    } else {
        echo "No tienes permisos para modificar este ticket.";
    }
} else {
    echo "Acceso no permitido.";
}
