<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require 'conexion.php';
$sql_tecnicos = "
    SELECT u.id, u.nombre 
    FROM usuarios u
    INNER JOIN rol r ON u.id_rol = r.id
    WHERE r.nombre = 'Tecnico'
";

$result_tecnicos = mysqli_query($conexion, $sql_tecnicos);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link href="img/favicon.png" rel="icon" type="image/png">

    <!-- Bootstrap (igual que en login) -->
    <link rel="stylesheet" href="css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/lib/font-awesome/font-awesome.min.css">

    <!-- Estilos personalizados -->
    <style>
        body {
            background: linear-gradient(135deg, #00416A, #E4E5E6);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ticket-container {
            background: #fff;
            margin: 60px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.2);
            max-width: 450px;
            width: 100%;
            text-align: center;
        }

        .ticket-container h2 {
            margin-bottom: 25px;
            color: #00416A;
            font-weight: 600;
        }

        .form-control, textarea {
            border-radius: 10px;
            margin-bottom: 15px;
            padding: 12px;
            width: 100%;
            border: 1px solid #ccc;
            resize: none;
        }

        .btn-ticket {
            background: #00416A;
            color: #fff;
            font-weight: bold;
            border-radius: 10px;
            padding: 12px;
            transition: 0.3s;
            width: 100%;
            border: none;
        }

        .btn-ticket:hover {
            background: #006699;
        }

        .extra-links {
            margin-top: 15px;
            font-size: 14px;
        }

        .extra-links a {
            color: #00416A;
            text-decoration: none;
        }

        .extra-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="ticket-container">
        <h2>Crear Nuevo Ticket</h2>

        <form method="POST" action="guardar_ticket.php">
            <input type="text" name="titulo" class="form-control" placeholder="Título del ticket" required>
            <textarea name="descripcion" rows="5" class="form-control" placeholder="Describe el problema" required></textarea>
            <label for="tecnico_id">Asignar a Técnico (opcional):</label>
            <select name="tecnico_id" id="tecnico_id">
                <option value="">-- Sin asignar --</option>
                <?php while($tecnico = mysqli_fetch_assoc($result_tecnicos)): ?>
                    <option value="<?= $tecnico['id'] ?>"><?= htmlspecialchars($tecnico['nombre']) ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit" class="btn-ticket">Crear Ticket</button>
        </form>

        <div class="extra-links">
            <a href="dashboard_<?php echo strtolower($_SESSION['rol']); ?>.php"> Volver al Dashboard</a>
        </div>
    </div>

</body>
</html>

