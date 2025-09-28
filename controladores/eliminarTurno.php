<?php
session_start();
include_once '../bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id_turno'])) {
    $id_turno = $_GET['id_turno'];
    $errores = [];

    // ------Validaciones-----

    if(empty($id_turno)) {
        $errores[] = "ID de turno es obligatorio.";
    } elseif (!ctype_digit($id_turno)) {
        $errores[] = "ID de turno inválido.";
    }

    if(empty($errores)){
        $sql = $conexion->prepare("DELETE FROM turnos WHERE id = :id");
        $sql->bindParam(':id', $id_turno, PDO::PARAM_INT);
        $sql->execute();

        $_SESSION['mensaje'] = "Turno eliminado exitosamente.";
        header("Location: ../vista_consulta_general.php");
        exit();
    } else {
        $_SESSION['mensaje'] = $errores;
        header("Location: ../vista_consulta_general.php");
        exit();
    }

} else {
    $_SESSION['mensaje'] = "Error al procesar la solicitud.";
    header("Location: ../vista_consulta_general.php");
    exit();
}
?>
