<?php
session_start();
if ($_SESSION['rol'] != 'Administrador') {
    header("Location: login.php");
    exit();
}

// 🔹 Simulación de usuarios y tickets (después conectarás a BD)
require 'conexion.php';

$sql_usuarios = "
    SELECT u.id, u.nombre, u.correo, r.nombre AS rol
    FROM usuarios u
    INNER JOIN rol r ON u.id_rol = r.id
";
$result_usuarios = mysqli_query($conexion, $sql_usuarios);

$usuarios = [];
if ($result_usuarios && mysqli_num_rows($result_usuarios) > 0) {
    while ($row = mysqli_fetch_assoc($result_usuarios)) {
        $usuarios[] = $row;
    }
}

$sql_tickets = "
    SELECT 
        t.id,
        t.titulo,
        t.descripcion,
        t.estado,
        cliente.nombre AS cliente_nombre,
        tecnico.nombre AS tecnico_nombre
    FROM tickets t
    INNER JOIN usuarios cliente ON t.usuario_id = cliente.id
    LEFT JOIN usuarios tecnico ON t.tecnico_id = tecnico.id
    ORDER BY t.fecha_creacion DESC
";

$result_tickets = mysqli_query($conexion, $sql_tickets);

$tickets = [];
if ($result_tickets && mysqli_num_rows($result_tickets) > 0) {
    while ($row = mysqli_fetch_assoc($result_tickets)) {
        $tickets[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrador</title>
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
            max-width: 1200px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h1 {
            color: #6f42c1;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            color: #6f42c1;
            margin-top: 30px;
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
            background: #6f42c1;
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
        .btn-edit { background: #0078d7; }
        .btn-edit:hover { background: #005fa3; }
        .btn-delete { background: #d9534f; }
        .btn-delete:hover { background: #c9302c; }

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

        .welcome {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Panel de Administración</h1>
        <p class="welcome">Bienvenid@ <strong><?php echo $_SESSION['usuario']; ?></strong> </p>

        <!-- Crear nuevo ticket -->
         
        <a href="crear_tecnico.php" class="btn-crear">Crear Técnico</a>
       <!-- <a href="nvo_ticket.php" class="btn-crear">Crear Nuevo Ticket</a>  -->

        <!-- Gestión de usuarios -->
        <h2>Gestión de Usuarios</h2>


        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?php echo $u["id"]; ?></td>
                <td><?php echo $u["nombre"]; ?></td>
                <td><?php echo $u["correo"]; ?></td>
                <td><?php echo $u["rol"]; ?></td>
                <td>
                    <?php if ($u['rol'] === 'Tecnico'): ?>
                        <a href="eliminar_usuario.php?id=<?= $u['id'] ?>" class="btn btn-delete" onclick="return confirm('¿Eliminar técnico?')">Eliminar</a>
                    <?php elseif ($u['rol'] === 'Cliente'): ?>
                        <a href="eliminar_usuario.php?id=<?= $u['id'] ?>" class="btn btn-delete" onclick="return confirm('¿Eliminar cliente?')">Eliminar</a>
                    <?php else: ?>
                        <!-- No se puede eliminar a admins -->
                        <span>-</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- Gestión de tickets -->
        <h2>Gestión de Tickets</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Título</th>
                <th>Estado</th>
                <th>Técnico Asignado</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($tickets as $t): ?>
            <tr>
                <form method="POST" action="actualizar_ticket.php">
                    <td><?php echo $t["id"]; ?></td>
                    <td><?php echo htmlspecialchars($t["cliente_nombre"]); ?></td>
                    <td><?php echo htmlspecialchars($t["titulo"]); ?></td>

                    <!-- Estado -->
                    <td>
                        <input type="hidden" name="ticket_id" value="<?= $t['id'] ?>">
                        <select name="estado">
                            <option value="Abierto" <?= $t['estado'] === 'Abierto' ? 'selected' : '' ?>>Abierto</option>
                            <option value="En Proceso" <?= $t['estado'] === 'En Proceso' ? 'selected' : '' ?>>En Proceso</option>
                            <option value="Cerrado" <?= $t['estado'] === 'Cerrado' ? 'selected' : '' ?>>Cerrado</option>
                        </select>
                    </td>

                    <!-- Técnico -->
                    <td>
                        <select name="tecnico_id">
                            <option value="">-- Sin asignar --</option>
                            <?php
                                $tecnicos_q = mysqli_query($conexion, "
                                    SELECT u.id, u.nombre 
                                    FROM usuarios u
                                    INNER JOIN rol r ON u.id_rol = r.id
                                    WHERE r.nombre = 'Tecnico'
                                ");
                                while ($tec = mysqli_fetch_assoc($tecnicos_q)):
                            ?>
                                <option value="<?= $tec['id'] ?>" <?= ($t['tecnico_nombre'] === $tec['nombre']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tec['nombre']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </td>

                    <!-- Botón de acción -->
                    <td>
                        <button type="submit" class="btn btn-edit">Actualizar</button>
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
