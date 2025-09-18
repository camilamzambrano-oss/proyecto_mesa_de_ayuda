<?php

session_start();
if ($_SESSION['rol'] != 'Cliente') {
    header("Location: login.php");
    exit();
}

require 'conexion.php';

// Consultar tickets del cliente desde la BD
$usuario_id = $_SESSION['id'];
$sqlTickets = "
    SELECT t.id, t.descripcion, t.estado, u.nombre AS nombre_tecnico
    FROM tickets t
    LEFT JOIN usuarios u ON t.tecnico_id = u.id
    WHERE t.usuario_id = '$usuario_id'
    ORDER BY t.fecha_creacion DESC
";
$result = mysqli_query($conexion, $sqlTickets);

$tickets = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tickets[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Cliente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h1 {
            color: #0078d7;
            text-align: center;
            margin-bottom: 20px;
        }

        .welcome {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }

        h2 {
            color: #0078d7;
            margin-top: 30px;
        }

        .btn-crear {
            display: inline-block;
            margin: 15px 0;
            background: #0078d7;
            padding: 10px 18px;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            text-decoration: none;
        }
        .btn-crear:hover {
            background: #005fa3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background: #0078d7;
            color: white;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .logout {
            display: inline-block;
            margin-top: 20px;
            background: #dc3545;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
        }
        .logout:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Panel Cliente</h1>
        <p class="welcome">Bienvenid@ <strong><?php echo $_SESSION['usuario']; ?></strong></p>

        <!-- Botón para crear ticket -->
        <a href="nvo_ticket.php" class="btn-crear">Crear Nuevo Ticket</a>

        <!-- Listado de tickets -->
        <h2>Mis Tickets</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Técnico Asignado</th>
                <th>Acciones</th>
            </tr>
            <?php if (!empty($tickets)): ?>
                <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?php echo $ticket["id"]; ?></td>
                    <td><?php echo $ticket["descripcion"]; ?></td>
                    <td><?php echo $ticket["estado"]; ?></td>
                    <td>
    <form method="POST" action="asignar_tecnico.php">
        <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
        <select name="tecnico_id" onchange="this.form.submit()">
            <option value="">-- Sin asignar --</option>
            <?php
                // Obtener técnicos desde la base de datos
                $sql_tecnicos = "
                    SELECT u.id, u.nombre 
                    FROM usuarios u
                    INNER JOIN rol r ON u.id_rol = r.id
                    WHERE r.nombre = 'Tecnico'
                ";
                $tecnicos = mysqli_query($conexion, $sql_tecnicos);

                while ($tec = mysqli_fetch_assoc($tecnicos)):
            ?>
                <option value="<?= $tec['id'] ?>" 
                    <?= ($ticket['nombre_tecnico'] == $tec['nombre']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tec['nombre']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>
</td>
<td>
    <form method="POST" action="eliminar_ticket.php" onsubmit="return confirm('¿Estás seguro de eliminar este ticket?');">
        <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
        <button type="submit" style="background-color: #dc3545; color: white; padding: 6px 12px; border: none; border-radius: 5px;">
            Eliminar
        </button>
    </form>
</td>
</tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No has creado ningún ticket aún</td>
                </tr>
            <?php endif; ?>
        </table>

        <div style="text-align: center;">
            <a href="logout.php" class="logout">Cerrar sesión</a>
        </div>
    </div>
</body>
</html>
