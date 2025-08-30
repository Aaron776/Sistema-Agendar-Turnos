<?php
session_start();
include_once '../bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id_turno'])) {
    $id_turno = $_GET['id_turno'];

    $sql = $conexion->prepare("DELETE FROM turnos WHERE id = :id");
    $sql->bindParam(':id', $id_turno);
    $sql->execute();

    if ($sql == true) {
        $_SESSION['mensaje'] = "Turno eliminado correctamente."; // Mensaje de éxito en sesión
        header("Location: ../vista_consulta_general.php");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el turno.";
        exit();
    }
}else{
    echo "Error al eliminar el turno";
    exit();
}


?>