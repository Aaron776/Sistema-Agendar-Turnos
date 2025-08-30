<?php
session_start();
include_once '../bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nombre']) && isset($_POST['usuario'])) {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $nombre   = trim($_POST['nombre']);
    $usuario  = trim($_POST['usuario']);
    $errores = [];

    // ---------------- VALIDACIONES ----------------
    if (strlen($nombre) > 100) {
        $errores[] = "El nombre no debe superar los 100 caracteres.";
    } elseif (!preg_match('/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/u', $nombre)) {
        $errores[] = "El nombre solo debe contener letras y espacios.";
    } elseif (strlen($nombre) < 10) {
        $errores[] = "El nombre debe tener al menos 10 caracteres.";
    } elseif (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    } elseif ($nombre !== strip_tags($nombre)) {
        $errores[] = 'No se permiten etiquetas HTML en el nombre';
    } elseif (preg_match('/(viagra|casino|bitcoin|porno)/i', $nombre)) {
        $errores[] = 'El nombre contiene contenido no permitido';
    }

    if (empty($usuario)) {
        $errores[] = "El usuario es obligatorio.";
    } elseif ($usuario !== strip_tags($usuario)) {
        $errores[] = 'No se permiten etiquetas HTML en el usuario';
    } elseif (preg_match('/(viagra|casino|bitcoin|porno)/i', $usuario)) {
        $errores[] = 'El usuario contiene contenido no permitido';
    } elseif (strlen($usuario) > 100) {
        $errores[] = "El usuario no debe superar los 100 caracteres.";
    }

    if (empty($email)) {
        $errores[] = "El email es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido.";
    }

    if (empty($password)) {
        $errores[] = "La contraseña es obligatoria.";
    }

    // ---------------- RESULTADOS ----------------
    if (empty($errores)){ // Si no hay errores procedemos a registrar al usuario
        $password_encriptada = password_hash($password, PASSWORD_BCRYPT); // Encriptamos la contraseña antes de guardarla

        $sql = $conexion->prepare("INSERT INTO usuarios (nombre, usuario, email, password) VALUES (:nombre, :usuario, :email, :password)");
        $sql->bindParam(':email', $email);
        $sql->bindParam(':password', $password_encriptada);
        $sql->bindParam(':nombre', $nombre);
        $sql->bindParam(':usuario', $usuario);
        $sql->execute();

        $_SESSION['exito'] = "¡Registro exitoso! Tu cuenta ha sido creada correctamente.";
    } else {
        $_SESSION['errores'] = $errores;
    }

    // Siempre redirigimos de vuelta al formulario de registro
    header('Location: ../registro.php');
    exit();
} else {
    $_SESSION['errores'] = ["Error al registrar el usuario. Intenta de nuevo."];
    header('Location: ../registro.php');
    exit();
}
