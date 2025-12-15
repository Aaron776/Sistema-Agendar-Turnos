<?php
session_start();
include_once '../bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['usuario']) && !empty($_POST['password'])) {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $errores = [];

    // Validaciones
    if (empty($usuario)) {
        $errores[] = "El usuario es obligatorio.";
    } elseif ($usuario !== strip_tags($usuario)) {
        $errores[] = 'No se permiten etiquetas HTML en el usuario';
    } elseif (preg_match('/(viagra|casino|bitcoin|porno)/i', $usuario)) {
        $errores[] = 'El usuario contiene contenido no permitido';
    } elseif (strlen($usuario) > 100) {
        $errores[] = "El usuario no debe superar los 100 caracteres.";
    }

    if (empty($password)) {
        $errores[] = "La contraseña es obligatoria.";
    }

    // Si no hay errores de Validacion procede el usuario a ingresar al sistema
    if(empty($errores)){

        // Traer usuario de la base de datos que coincida con el usuario y la contraseña ingresado en el formulario de login
        $sql = $conexion->prepare("SELECT id, email, nombre, usuario, cedula, password, rol FROM usuarios WHERE usuario = :usuario LIMIT 1");
        $sql->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $sql->execute();
        $usuario_login = $sql->fetch(PDO::FETCH_OBJ);

        // Verificar existencia de ese usuario en la base de datos si es verdadero o true y desencriptar la contraseña para comparar
        if ($usuario_login && password_verify($password, $usuario_login->password)) {
            $_SESSION['id'] = $usuario_login->id;
            $_SESSION['email'] = $usuario_login->email;
            $_SESSION['nombre'] = $usuario_login->nombre;
            $_SESSION['usuario'] = $usuario_login->usuario;
            $_SESSION['rol'] = $usuario_login->rol;
            $_SESSION['cedula'] = $usuario_login->cedula;
            $_SESSION['logueado'] = true;

            if ($usuario_login->rol === 'admin') {
                header('Location: ../admin.php');
            } else {
                header('Location: ../cliente.php');
            }
            exit();
        } else {
            // Error si las credenciales son incorrectas
            $_SESSION['errores'] = ["Credenciales incorrectas. Intenta de nuevo."];
            header('Location: ../index.php');
            exit();
        }
    } else {
        // Si hay errores de validación, redirige al index con los errores
        $_SESSION['errores'] = $errores;
        header('Location: ../index.php');
        exit();
    }
   
} else {
    // Error si no se enviaron correctamente los datos del formulario
    $_SESSION['errores'] = ["Error al enviar los datos del formulario"];
    header('Location: ../index.php');
    exit();
}
?>
