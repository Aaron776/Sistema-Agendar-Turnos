<?php
session_start();
include_once '../bd/conexion.php';

// Registrar turno
if(isset($_POST['nombre']) && isset($_POST['cedula']) && isset($_POST['nota_adicional']) && isset($_POST['fecha_cita']) && isset($_POST['servicio']) && isset($_POST['hora_cita']) && isset($_POST['id_usuario'])){
    $servicio = $_POST['servicio'];
    $nota_adicional = $_POST['nota_adicional'];
    $fecha_cita = $_POST['fecha_cita'];
    $hora_cita = $_POST['hora_cita'];
    $id_usuario = $_POST['id_usuario'];
    $cedula = $_POST['cedula'];
    $errores = [];

    // ---------------- VALIDACIONES ----------------
    if (empty($servicio)) {
        $errores[] = "El servicio es obligatorio.";
    } elseif ($servicio !== strip_tags($servicio)) {
        $errores[] = 'No se permiten etiquetas HTML en el servicio';
    }  

    if (empty($cedula)) {
        $errores[] = "La cedula es obligatoria.";
    } elseif (strlen($cedula) > 10) {
        $errores[] = "La cedula debe tener un máximo de 10 caracteres.";
    }

    if ($nota_adicional !== strip_tags($nota_adicional)) {
        $errores[] = 'No se permiten etiquetas HTML en la nota adicional';
    }elseif (strlen($nota_adicional) > 100) {
        $errores[] = "La nota adicional debe tener un máximo de 100 caracteres.";
    }

    if (empty($fecha_cita)) {
        $errores[] = "La fecha de la cita es obligatoria.";
    }

    if (empty($hora_cita)) {
        $errores[] = "La hora de la cita es obligatoria.";
    }


    // ---------------- VERIFICAR SI YA EXISTE TURNO AGENDADO EN ESA FECHA Y HORA PREVIAMENTE ----------------
    if (empty($errores)) {
        $check = $conexion->prepare(" SELECT id FROM turnos WHERE fecha_cita = :fecha_cita AND hora_cita = :hora_cita");
        $check->bindParam(':fecha_cita', $fecha_cita);
        $check->bindParam(':hora_cita', $hora_cita);
        $check->execute();

        if ($check->rowCount() > 0) {
            $errores[] = "Ya existe un turno agendado en esa fecha y hora. Por favor selecciona otra.";
        }
    }



    if (empty($errores)) {
        $sql = $conexion->prepare("INSERT INTO turnos (servicio_id, usuario_id, fecha_cita, hora_cita,cedula,nota_adicional) VALUES (:servicio, :id_usuario, :fecha_turno, :hora_turno,:cedula,:nota_adicional)");
        $sql->bindParam(':servicio', $servicio);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':fecha_turno', $fecha_cita);
        $sql->bindParam(':hora_turno', $hora_cita);
        $sql->bindParam(':cedula', $cedula);
        $sql->bindParam(':nota_adicional', $nota_adicional);
        $sql->execute();

        // Obtener el ID del ultimo turno insertado
        $id_turno = $conexion->lastInsertId();

        header("Location: ../confirmacion_turno.php?id_turno=$id_turno"); // Redirigir a la página de confirmación_turno.php y atraves de la URL envio el id de ese ultimo turno
        exit;
    }else{
        $_SESSION['errores'] = $errores;
        header("Location: ../cliente.php");
        exit;
    }
}
?>