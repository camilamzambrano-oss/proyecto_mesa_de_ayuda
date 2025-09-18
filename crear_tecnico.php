<?php
session_start();
require 'conexion.php';

if ($_SESSION['rol'] != 'Administrador') {
    header("Location: login.php");
    exit();
}

// Procesamiento del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = trim($_POST['documento']);
    $nombre = trim($_POST['nombre']);
    $celular = trim($_POST['celular']);
    $correo = trim($_POST['correo']);
    $clave     = $_POST['clave'];

    // Verificar si ya existe correo o documento
    $check = mysqli_query($conexion, "SELECT id FROM usuarios WHERE correo = '$correo' OR documento = '$documento'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>
            alert('Ya existe un usuario con este correo o documento.');
            window.history.back();
        </script>";
        exit();
    }

    // Obtener ID del rol Técnico
    $rol_query = mysqli_query($conexion, "SELECT id FROM rol WHERE nombre = 'Tecnico'");
    $rol = mysqli_fetch_assoc($rol_query);
    $id_rol = $rol['id'];

    // Insertar nuevo técnico
    $stmt = mysqli_prepare($conexion, "INSERT INTO usuarios (documento, nombre, correo, clave, celular, id_rol) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssssi", $documento, $nombre, $correo, $clave, $celular, $id_rol);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo "<script>
        alert('Técnico creado correctamente.');
        window.location.href = 'dashboard_admin.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Técnico - Mesa de Ayuda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <link href="img/favicon.png" rel="icon" type="image/png">
    <link rel="stylesheet" href="css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/lib/font-awesome/font-awesome.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #00416A, #E4E5E6);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.2);
            max-width: 450px;
            width: 100%;
            text-align: center;
        }

        .login-container img {
            width: 90px;
            margin-bottom: 15px;
        }

        .login-container h2 {
            margin-bottom: 25px;
            color: #00416A;
            font-weight: 600;
        }

        .form-control {
            border-radius: 10px;
            margin-bottom: 15px;
            padding: 12px;
        }

        .btn-login {
            background: #00416A;
            color: #fff;
            font-weight: bold;
            border-radius: 10px;
            padding: 12px;
            transition: 0.3s;
            width: 100%;
            border: none;
        }

        .btn-login:hover {
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

    <div class="login-container">
        <!-- Logo -->
        <img src="https://st3.depositphotos.com/16781356/34464/v/450/depositphotos_344646702-stock-illustration-website-icon-vector-design-illustration.jpg" alt="Logo de la empresa">

        <h2>Crear Técnico</h2>

        <form method="POST">
            <input type="text" name="documento" class="form-control" placeholder="Documento" required>
            <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" required>
            <input type="text" name="celular" class="form-control" placeholder="Celular" required>
            <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" required>
            <input type="password" name="clave" class="form-control" placeholder="Contraseña" required>

            <button type="submit" class="btn-login">Guardar Técnico</button>
        </form>

        <div class="extra-links">
            <a href="dashboard_admin.php">← Volver al Dashboard</a>
        </div>
    </div>

</body>
</html>
