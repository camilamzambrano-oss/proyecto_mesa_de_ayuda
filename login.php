<?php
session_start();
include("conexion.php");

// Redirección si ya hay sesión activa
if (isset($_SESSION['usuario'])) {
    switch ($_SESSION['rol']) {
        case 'Administrador':
            header("Location: dashboard_admin.php");
            break;
        case 'Tecnico':
            header("Location: dashboard_tecnico.php");
            break;
        default:
            header("Location: dashboard_cliente.php");
    }
    exit();
}

// Procesar login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['correo'], $_POST['clave'])) {
    $correo = $_POST['correo'];
    $clave  = $_POST['clave'];

    // Consulta para obtener datos del usuario + nombre del rol
    $sql = "SELECT usuarios.*, rol.nombre AS nombre_rol
            FROM usuarios
            JOIN rol ON usuarios.id_rol = rol.id
            WHERE usuarios.correo = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Verificar contraseña
        if ($clave === $usuario['clave']) {
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['rol']     = $usuario['nombre_rol'];
            $_SESSION['id']      = $usuario['id'];

            // Redirigir según rol
            switch ($usuario['nombre_rol']) {
                case 'Administrador':
                    header("Location: dashboard_admin.php");
                    break;
                case 'Tecnico':
                    header("Location: dashboard_tecnico.php");
                    break;
                default:
                    header("Location: dashboard_cliente.php");
            }
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location='login.php';</script>";
    }
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login - Mesa de Ayuda</title>

    <link href="img/favicon.png" rel="icon" type="image/png">
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

        .login-container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.2);
            max-width: 400px;
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
        <img src="https://st3.depositphotos.com/16781356/34464/v/450/depositphotos_344646702-stock-illustration-website-icon-vector-design-illustration.jpg" alt="Logo de la empresa">

        <h2>Iniciar Sesión</h2>
        
        <form method="POST" action="login.php">
            <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" required>
            <input type="password" name="clave" class="form-control" placeholder="Contraseña" required>
            
            <button type="submit" class="btn btn-login">Ingresar</button>
        </form>

        <div class="extra-links">
            <a href="reset-password.html">¿Olvidaste tu contraseña?</a><br>
            ¿No tienes cuenta? <a href="registro.php">Regístrate</a>
        </div>
    </div>

</body>
</html>
