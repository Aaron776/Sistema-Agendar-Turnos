<?php
session_start();
include_once '../bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cedula']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nombre']) && isset($_POST['usuario'])) {
    $nombre   = trim($_POST['nombre']);
    $usuario  = trim($_POST['usuario']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $cedula   = trim($_POST['cedula']);
    $errores = [];

    // ---------------- VALIDACIONES ----------------
    if (empty($nombre)) {
    $errores[] = "El nombre es obligatorio.";
} elseif (strlen($nombre) > 100) {
    $errores[] = "El nombre no debe superar los 100 caracteres.";
} elseif (!preg_match('/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/u', $nombre)) {
    $errores[] = "El nombre solo debe contener letras y espacios.";
} elseif (strlen($nombre) < 10) {
    $errores[] = "El nombre debe tener al menos 10 caracteres.";
} elseif ($nombre !== strip_tags($nombre)) {
    $errores[] = 'No se permiten etiquetas HTML en el nombre';
} elseif (preg_match('/(viagra|casino|bitcoin|porno)/i', $nombre)) {
    $errores[] = 'El nombre contiene contenido no permitido';
}


    if (empty($cedula)) {
        $errores[] = "La cédula es obligatoria.";
    } elseif (!ctype_digit($cedula)) {
        $errores[] = "La cédula solo debe contener números.";
    } elseif (strlen($cedula) > 10) {
        $errores[] = "La cédula debe tener un máximo de 10 dígitos.";
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

    // Validación de email mejorada
    if (empty($email)) {
        $errores[] = "El email es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido.";
    } elseif (strlen($email) > 255) {
        $errores[] = "El email es demasiado largo.";
    }

    if (empty($password)) {
        $errores[] = "La contraseña es obligatoria.";
    } elseif (strlen($password) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errores[] = "La contraseña debe contener al menos una letra mayúscula.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errores[] = "La contraseña debe contener al menos un número.";
    } elseif (!preg_match('/[\W]/', $password)) {
        $errores[] = "La contraseña debe contener al menos un carácter especial.";
    }


    // Verificar duplicados
    if (empty($errores)) {
        try {
            $check_sql = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email OR usuario = :usuario OR cedula = :cedula");
            $check_sql->bindParam(':email', $email, PDO::PARAM_STR);
            $check_sql->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $check_sql->bindParam(':cedula', $cedula, PDO::PARAM_STR);
            $check_sql->execute();

            if ($check_sql->fetchColumn() > 0) {
                $errores[] = "El email, usuario o cédula ya están registrados.";
            }
        } catch (PDOException $e) {
            error_log("Error verificando duplicados: " . $e->getMessage());
            $errores[] = "Error interno. Intenta nuevamente.";
        }
    }

    // ---------------- RESULTADOS ----------------
    if (empty($errores)){ // Si no hay errores procedemos a registrar al usuario
        $password_encriptada = password_hash($password, PASSWORD_BCRYPT); // Encriptamos la contraseña antes de guardarla

        $sql = $conexion->prepare("INSERT INTO usuarios (nombre, usuario, email, cedula, password) VALUES (:nombre, :usuario, :email, :cedula, :password)");
        $sql->bindParam(':email', $email);
        $sql->bindParam(':password', $password_encriptada);
        $sql->bindParam(':nombre', $nombre);
        $sql->bindParam(':usuario', $usuario);
        $sql->bindParam(':cedula', $cedula);
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
