<?php
session_start();
require 'conexion.php';

if ($_SESSION['rol'] != 'Administrador') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    if ($_SESSION['id'] == $id) {
        exit("No puedes eliminarte a ti mismo.");
    }

    // Verificar si el usuario tiene tickets
    $stmt_check = mysqli_prepare($conexion, "SELECT COUNT(*) FROM tickets WHERE usuario_id = ? OR tecnico_id = ?");
    mysqli_stmt_bind_param($stmt_check, "ii", $id, $id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_bind_result($stmt_check, $count);
    mysqli_stmt_fetch($stmt_check);
    mysqli_stmt_close($stmt_check);

    if ($count > 0) {
        echo "<script>
            alert('No se puede eliminar el usuario porque tiene tickets asignados o creados.');
            window.location.href = 'dashboard_admin.php';
        </script>";
        exit();
    }

    // Eliminar usuario si no tiene tickets
    $stmt = mysqli_prepare($conexion, "DELETE FROM usuarios WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header("Location: dashboard_admin.php");
exit();
