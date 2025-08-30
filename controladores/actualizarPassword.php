<?php
session_start();
include_once "../bd/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password']) && !empty($_POST['password_nueva'])){
    $password_actual = trim($_POST['password']); // Obtener la contraseña actual del formulario
    $password_nueva = trim($_POST['password_nueva']); // Obtener la nueva contraseña del formulario
    $id_usuario = $_SESSION['id'];
    $errores = [];

    //Validaciones
    if (empty($password_actual)) {
        $errores['password_actual'] = "La contraseña es obligatoria.";
    }

    if (empty($password_nueva)) {
        $errores['password_nueva'] = "La contraseña es obligatoria.";
    }

    $sql = $conexion->prepare("SELECT password FROM usuarios WHERE id = :id");
    $sql->bindParam(':id', $id_usuario);
    $sql->execute();
    $usuario = $sql->fetch(PDO::FETCH_OBJ);

    try {
        if (empty($errores) && $usuario == true && password_verify($password_actual, $usuario->password)) {
            $password_encriptada = password_hash($password_nueva, PASSWORD_BCRYPT); // Encriptar la contraseña nueva
            $sql = $conexion->prepare("UPDATE usuarios SET password = :password WHERE id = :id");
            $sql->bindParam(':password', $password_encriptada);
            $sql->bindParam(':id', $id_usuario);
            $sql->execute();
            $_SESSION['exito'] = "Contraseña actualizada correctamente";
        } else {
            $_SESSION['errores'] = $errores;
        }

        header("Location: ../cambiar_password.php");
        exit();
    } catch (Exception $e) {
        echo "Error al actualizar la contraseña: " . $e->getMessage();
    }
}

?>