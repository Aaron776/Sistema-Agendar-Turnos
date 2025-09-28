<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include_once '../bd/conexion.php';

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $errores = [];

    //Validaciones
    if (empty($email)) {
        $errores[] = "El email es obligatorio.";
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido.";
    }

    // 1. Verificar si existe el usuario
    $sql = $conexion->prepare("SELECT id, email FROM usuarios WHERE email = :email");
    $sql->bindParam(':email', $email);
    $sql->execute();
    $usuario = $sql->fetch(PDO::FETCH_OBJ);

    if (empty($errores) && $usuario==true) { // Si el usuario existe en la base de datos
        // 2. Generar código aleatorio de 10 caracteres
        $nuevaPassword = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"), 0, 10);

        // 3. Hashear el código correcto
        $codigoHasheado = password_hash($nuevaPassword, PASSWORD_DEFAULT);

        // 4. Guardar en la base de datos
        $update = $conexion->prepare("UPDATE usuarios SET password = :password WHERE id = :id");
        $update->bindParam(':password', $codigoHasheado, PDO::PARAM_STR);
        $update->bindParam(':id', $usuario->id,PDO::PARAM_INT);
        $update->execute();

        // 5. Enviar correo con PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'aronortiz759@gmail.com'; // Tu correo
            $mail->Password   = 'uvxt gvyl sscp whim';    // Contraseña o App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('aronortiz759@gmail.com', 'Sistema de Turnos');
            $mail->addAddress($usuario->email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña - Sistema de Turnos';
            $mail->Body    = "
                <h2>Recuperación de contraseña</h2>
                <p>Hola, has solicitado recuperar tu contraseña.</p>
                <p>Tu nueva contraseña es: <b>{$nuevaPassword}</b></p>
                <p>Te recomendamos cambiarla después de iniciar sesión.</p>
            ";

            $mail->send();
            $_SESSION['exito'] = "¡Correo enviado! Por favor revisa tu bandeja de entrada.";
        } catch (Exception $e) {
            echo "❌ Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['errores'] = $errores;
    }

    header("Location: ../codigo_cambio_password.php"); // Redirige al login
    exit();
} else {
    $_SESSION['errores'] = ["Debes ingresar un correo válido."];
    header("Location: ../codigo_cambio_password.php");
    exit();
}
