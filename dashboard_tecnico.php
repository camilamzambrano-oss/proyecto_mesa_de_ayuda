<?php
session_start();
if ($_SESSION['rol'] != 'Tecnico') {
    header("Location: login.php");
    exit();
}

require 'conexion.php'; // Asegúrate de tener conexión

$tecnico_id = $_SESSION['id']; // El ID del técnico logueado

$sql = "
    SELECT 
        t.id,
        t.titulo,
        t.descripcion,
        t.estado,
        u.nombre AS cliente_nombre
    FROM tickets t
    INNER JOIN usuarios u ON t.usuario_id = u.id
    WHERE t.tecnico_id = $tecnico_id
    ORDER BY t.fecha_creacion DESC
";

$result = mysqli_query($conexion, $sql);

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
    <title>Dashboard Técnico</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h1 {
            color: #28a745;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            color: #28a745;
            margin-top: 30px;
        }

        .welcome {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
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
            background: #28a745;
            color: white;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-resolver { background: #0078d7; }
        .btn-resolver:hover { background: #005fa3; }

        .btn-crear {
            display: inline-block;
            margin: 15px 0;
            background: #28a745;
            padding: 10px 18px;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            text-decoration: none;
        }
        .btn-crear:hover {
            background: #218838;
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
        <h1>Panel Técnico</h1>
        <p class="welcome">Bienvenid@ <strong><?php echo $_SESSION['usuario']; ?></strong></p>

        <!-- Crear nuevo ticket 
        <a href="nvo_ticket.php" class="btn-crear">Crear Nuevo Ticket</a> -->

        <!-- Tickets asignados -->
        <h2>Tickets Asignados</h2>
        <table>
            <tr>
                <th>ID Ticket</th>
                <th>Cliente</th>
                <th>Asunto</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
            <?php foreach ($tickets as $t): ?>
            <tr>
                <form method="POST" action="actualizar_ticket.php">
                    <td><?php echo $t["id"]; ?></td>
                    <td><?php echo htmlspecialchars($t["cliente_nombre"]); ?></td>
                    <td><?php echo htmlspecialchars($t["titulo"]); ?></td>
                    <td>
                        <input type="hidden" name="ticket_id" value="<?= $t['id'] ?>">
                        <select name="estado">
                            <option value="Abierto" <?= $t['estado'] === 'Abierto' ? 'selected' : '' ?>>Abierto</option>
                            <option value="En Proceso" <?= $t['estado'] === 'En Proceso' ? 'selected' : '' ?>>En Proceso</option>
                            <option value="Cerrado" <?= $t['estado'] === 'Cerrado' ? 'selected' : '' ?>>Cerrado</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-resolver">Actualizar Estado</button>
                    </td>
                </form>
            </tr>
            <?php endforeach; ?>

        </table>

        <div style="text-align: center;">
            <a href="logout.php" class="logout">Cerrar sesión</a>
        </div>
    </div>
</body>
</html>
