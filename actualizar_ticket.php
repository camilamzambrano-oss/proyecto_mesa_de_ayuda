<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['rol']) || !isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$rol = $_SESSION['rol'];
$usuario_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = intval($_POST['ticket_id']);
    $estado = $_POST['estado'];

    // Validar estado
    if (!in_array($estado, ['Abierto', 'En Proceso', 'Cerrado'])) {
        exit("Estado inválido");
    }

    if ($rol === 'Administrador') {
        // El administrador puede cambiar técnico asignado
        $tecnico_id = $_POST['tecnico_id'] !== '' ? intval($_POST['tecnico_id']) : null;

        if ($tecnico_id !== null) {
            $stmt = mysqli_prepare($conexion, "UPDATE tickets SET estado = ?, tecnico_id = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sii", $estado, $tecnico_id, $ticket_id);
        } else {
            $stmt = mysqli_prepare($conexion, "UPDATE tickets SET estado = ?, tecnico_id = NULL WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $estado, $ticket_id);
        }

    } elseif ($rol === 'Tecnico') {
        // El técnico solo puede cambiar el estado de sus tickets
        // Verificamos que el ticket esté asignado a él
        $check = mysqli_prepare($conexion, "SELECT id FROM tickets WHERE id = ? AND tecnico_id = ?");
        mysqli_stmt_bind_param($check, "ii", $ticket_id, $usuario_id);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) === 0) {
            exit("No tienes permiso para modificar este ticket.");
        }

        // Solo actualiza el estado, no el técnico
        $stmt = mysqli_prepare($conexion, "UPDATE tickets SET estado = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $estado, $ticket_id);

    } else {
        exit("Rol no autorizado.");
    }

    // Ejecutar actualización
    if (mysqli_stmt_execute($stmt)) {
        if ($rol === 'Administrador') {
            header("Location: dashboard_admin.php?msg=actualizado");
        } else {
            header("Location: dashboard_tecnico.php?msg=actualizado");
        }
        exit();
    } else {
        echo "Error al actualizar el ticket.";
    }
}
