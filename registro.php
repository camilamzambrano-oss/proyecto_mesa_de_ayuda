<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $documento = $_POST['documento'];
    $nombre    = $_POST['nombre'];
    $celular   = $_POST['celular'];
    $correo    = $_POST['correo'];
    $clave     = $_POST['clave'];
    $id_rol    = $_POST['id_rol'];

    $sql = "INSERT INTO usuarios (documento, nombre, celular, correo, clave, id_rol) 
            VALUES ('$documento', '$nombre', '$celular', '$correo', '$clave', $id_rol)";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Usuario registrado con éxito'); window.location='login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Registro - Mesa de Ayuda</title>

    <link href="img/favicon.png" rel="icon" type="image/png">
    <link rel="stylesheet" href="css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/lib/font-awesome/font-awesome.min.css">

    <!-- Estilos personalizados (mismos del login) -->
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

        .form-control, select {
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
        <!-- Logo superior -->
        <img src="https://st3.depositphotos.com/16781356/34464/v/450/depositphotos_344646702-stock-illustration-website-icon-vector-design-illustration.jpg" alt="Logo de la empresa">

        <h2>Registro de Usuario</h2>

        <!-- Formulario -->
        <form method="POST" action="registro.php">
            <input type="number" name="documento" class="form-control" placeholder="Documento" required>
            <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" required>
            <input type="number" name="celular" class="form-control" placeholder="Celular" required>
            <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" required>
            <input type="password" name="clave" class="form-control" placeholder="Contraseña" required>
            <select name="id_rol" class="form-control" required>
                <option value="" disabled selected>Selecciona un rol</option>
                <option value="1">Administrador</option>
                <option value="2">Técnico</option>
                <option value="3">Cliente</option>
            </select>


            <button type="submit" class="btn-login">Registrar</button>
        </form>

        <!-- Enlaces adicionales -->
        <div class="extra-links">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
        </div>
    </div>

</body>
</html>
