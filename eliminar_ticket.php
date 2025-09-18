<?php
session_start();
require 'conexion.php';

if ($_SESSION['rol'] !== 'Cliente' || !isset($_POST['ticket_id'])) {
    header("Location: login.php");
    exit();
}

$ticket_id = intval($_POST['ticket_id']);
$usuario_id = $_SESSION['id'];

// Validar que el ticket le pertenece al cliente
$sql_check = "SELECT id FROM tickets WHERE id = ? AND usuario_id = ?";
$stmt_check = mysqli_prepare($conexion, $sql_check);
mysqli_stmt_bind_param($stmt_check, "ii", $ticket_id, $usuario_id);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) === 0) {
    echo "No tienes permiso para eliminar este ticket.";
    exit();
}

// Eliminar el ticket
$sql_delete = "DELETE FROM tickets WHERE id = ?";
$stmt_delete = mysqli_prepare($conexion, $sql_delete);
mysqli_stmt_bind_param($stmt_delete, "i", $ticket_id);

if (mysqli_stmt_execute($stmt_delete)) {
    header("Location: dashboard_cliente.php?msg=ticket_eliminado");
    exit();
} else {
    echo "Error al eliminar el ticket.";
}