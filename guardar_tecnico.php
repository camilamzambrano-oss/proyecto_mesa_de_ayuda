<?php
session_start();
require 'conexion.php';

if ($_SESSION['rol'] != 'Administrador') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Obtener ID del rol 'Tecnico'
    $rol_query = mysqli_query($conexion, "SELECT id FROM rol WHERE nombre = 'Tecnico'");
    $rol = mysqli_fetch_assoc($rol_query);
    $id_rol = $rol['id'];

    // Verificar si ya existe el correo
    $check = mysqli_query($conexion, "SELECT id FROM usuarios WHERE correo = '$correo'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>
            alert('Este correo ya está registrado.');
            window.history.back();
        </script>";
        exit();
    }

    // Insertar técnico
    $stmt = mysqli_prepare($conexion, "INSERT INTO usuarios (nombre, correo, password, id_rol) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssi", $nombre, $correo, $password, $id_rol);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo "<script>
        alert('Técnico creado exitosamente.');
        window.location.href = 'dashboard_admin.php';
    </script>";
}
