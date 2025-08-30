<?php
session_start();
include_once '../bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['usuario']) && !empty($_POST['password'])) {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    try {
        // Traer usuario de la base de datos que coincida con el usuario y la contraseña ingresado en el formulario de login
        $sql = $conexion->prepare("SELECT id, email, nombre, usuario, password, rol FROM usuarios WHERE usuario = :usuario LIMIT 1");
        $sql->bindParam(':usuario', $usuario);
        $sql->execute();
        $usuario = $sql->fetch(PDO::FETCH_OBJ);

        // Verificar existencia de ese usuario en la base de datos si es cerdadero o true y desencriptar la contraseña para comparar
        if ($usuario==true && password_verify($password, $usuario->password)) {
            $_SESSION['id'] = $usuario->id;
            $_SESSION['email'] = $usuario->email;
            $_SESSION['nombre'] = $usuario->nombre;
            $_SESSION['usuario'] = $usuario->usuario;
            $_SESSION['rol'] = $usuario->rol;
            $_SESSION['logueado'] = true;

            if ($usuario->rol === 'admin') {
                header('Location: ../admin.php');
            } else {
                header('Location: ../cliente.php');
            }
            exit();
        } else {
            echo '<div class="alert alert-danger">Usuario o contraseña incorrectos</div>';
            exit();
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    echo '<div class="alert alert-danger">Error al enviar los datos del formulario</div>';
    exit();
}
?>
