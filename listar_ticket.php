<?php
require 'conexion.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['id'];
$result = mysqli_query($conn, "SELECT * FROM tickets WHERE usuario_id = '$usuario_id'");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tickets</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="login-container">
    <h1>Tickets</h1>
    <a href="nuevo_ticket.php"> Crear Ticket</a> | 
    <a href="dashboard_cliente.php">⬅ Volver al Dashboard</a>
    <table border="1" style="margin-top:20px; width:100%; background:#fff; color:#000;">
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Fecha</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['titulo']; ?></td>
            <td><?php echo $row['descripcion']; ?></td>
            <td><?php echo $row['estado']; ?></td>
            <td><?php echo $row['fecha_creacion']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
