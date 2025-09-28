<?php
session_start();
include_once "../bd/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password']) && !empty($_POST['password_nueva'])) {

    $password_actual = trim($_POST['password']); 
    $password_nueva  = trim($_POST['password_nueva']); 
    $id_usuario      = $_SESSION['id'];
    $errores = [];

    // Validaciones
    if (empty($password_actual)) {
        $errores['password_actual'] = "La contraseña actual es obligatoria.";
    }
    if (empty($password_nueva)) {
        $errores['password_nueva'] = "La nueva contraseña es obligatoria.";
    }
    if ($password_actual === $password_nueva) {
        $errores['password_nueva'] = "La nueva contraseña debe ser diferente a la actual.";
    }

    if (empty($errores)) {
        // Traer usuario de la BD
        $sql = $conexion->prepare("SELECT password FROM usuarios WHERE id = :id LIMIT 1");
        $sql->bindParam(':id', $id_usuario, PDO::PARAM_INT);
        $sql->execute();
        $usuario = $sql->fetch(PDO::FETCH_OBJ);

        if ($usuario && password_verify($password_actual, $usuario->password)) {
            // Encriptar nueva contraseña
            $password_encriptada = password_hash($password_nueva, PASSWORD_BCRYPT);

            // Actualizar contraseña
            $update = $conexion->prepare("UPDATE usuarios SET password = :password WHERE id = :id");
            $update->bindParam(':password', $password_encriptada, PDO::PARAM_STR);
            $update->bindParam(':id', $id_usuario, PDO::PARAM_INT);
            $update->execute();

            $_SESSION['exito'] = "Contraseña actualizada correctamente.";

        } else {
            $errores['password_actual'] = "La contraseña actual es incorrecta.";
            $_SESSION['errores'] = $errores;
        }

    } else {
        $_SESSION['errores'] = $errores;
    }

    header("Location: ../cambiar_password.php");
    exit();

} else {
    $_SESSION['errores'] = ["Error en la solicitud."];
    header("Location: ../cambiar_password.php");
    exit();
}
?>
