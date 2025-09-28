<?php
session_start();
include_once '../bd/conexion.php';

// Registrar turno
if (isset($_POST['nombre']) && isset($_POST['cedula']) && isset($_POST['nota_adicional']) && isset($_POST['fecha_cita']) && isset($_POST['servicio']) && isset($_POST['hora_cita']) && isset($_POST['id_usuario'])) {
    $servicio = trim($_POST['servicio']);
    $nota_adicional = trim($_POST['nota_adicional']);
    $fecha_cita = trim($_POST['fecha_cita']);
    $hora_cita = trim($_POST['hora_cita']);
    $id_usuario = trim($_POST['id_usuario']);
    $cedula = trim($_POST['cedula']);
    $errores = [];

    // ---------------- VALIDACIONES ----------------
    if (empty($servicio)) {
        $errores[] = "El servicio es obligatorio.";
    } elseif (!ctype_digit($servicio)) {
        $errores[] = "El servicio seleccionado no es válido.";
    }

    // Cédula
    if (empty($cedula)) {
        $errores[] = "La cédula es obligatoria.";
    } elseif (!ctype_digit($cedula)) {
        $errores[] = "La cédula solo debe contener números.";
    } elseif (strlen($cedula) > 10) {
        $errores[] = "La cédula debe tener un máximo de 10 dígitos.";
    }

    // Nota adicional (opcional pero limitada)
    if (!empty($nota_adicional) && strlen($nota_adicional) > 100) {
        $errores[] = "La nota adicional debe tener un máximo de 100 caracteres.";
    }

    // Fecha de cita
    if (empty($fecha_cita)) {
        $errores[] = "La fecha de la cita es obligatoria.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_cita)) {
        $errores[] = "La fecha no tiene un formato válido (YYYY-MM-DD).";
    } elseif (strtotime($fecha_cita) < strtotime(date('Y-m-d'))) {
        $errores[] = "No puedes agendar citas en fechas pasadas.";
    }

    // Hora de cita
    if (empty($hora_cita)) {
        $errores[] = "La hora de la cita es obligatoria.";
    } elseif (!preg_match('/^\d{2}:\d{2}$/', $hora_cita)) {
        $errores[] = "La hora no tiene un formato válido (HH:MM).";
    } else {
        // Validación rango 08:00 - 19:00
        list($hora, $minutos) = explode(':', $hora_cita);
        $hora_int = (int)$hora;
        $minutos_int = (int)$minutos;
        if ($hora_int < 8 || $hora_int > 19 || ($hora_int == 19 && $minutos_int > 0)) {
            $errores[] = "La cita debe ser entre 08:00 y 19:00.";
        }
    }


    // ID de usuario
    if (empty($id_usuario) || !ctype_digit($id_usuario)) {
        $errores[] = "El usuario no es válido.";
    }

    // ---------------- VERIFICAR SI YA EXISTE TURNO ----------------
    if (empty($errores)) {
        try {
            $check = $conexion->prepare("SELECT id FROM turnos WHERE fecha_cita = :fecha_cita AND hora_cita = :hora_cita AND servicio_id = :servicio");
            $check->bindParam(':fecha_cita', $fecha_cita, PDO::PARAM_STR);
            $check->bindParam(':hora_cita', $hora_cita, PDO::PARAM_STR);
            $check->bindParam(':servicio', $servicio, PDO::PARAM_INT);
            $check->execute();

            if ($check->rowCount() > 0) {
                $errores[] = "Ya existe un turno agendado en esa fecha y hora para este servicio. Por favor selecciona otra.";
            }
        } catch (PDOException $e) {
            error_log("Error verificando turno duplicado: " . $e->getMessage());
            $errores[] = "Error interno al verificar disponibilidad. Intenta nuevamente.";
        }
    }

    if (empty($errores)) {
        $sql = $conexion->prepare("INSERT INTO turnos (servicio_id, usuario_id, fecha_cita, hora_cita, cedula, nota_adicional) VALUES (:servicio, :id_usuario, :fecha_turno, :hora_turno, :cedula, :nota_adicional)");
        $sql->bindParam(':servicio', $servicio, PDO::PARAM_INT);
        $sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $sql->bindParam(':fecha_turno', $fecha_cita, PDO::PARAM_STR);
        $sql->bindParam(':hora_turno', $hora_cita, PDO::PARAM_STR);
        $sql->bindParam(':cedula', $cedula, PDO::PARAM_STR);
        $sql->bindParam(':nota_adicional', $nota_adicional, PDO::PARAM_STR);

        $sql->execute();

        // Obtener el ID del último turno insertado
        $id_turno = $conexion->lastInsertId();

        header("Location: ../confirmacion_turno.php?id_turno=$id_turno");
        exit;
    } else {
        $_SESSION['errores'] = $errores;
        header("Location: ../cliente.php");
        exit;
    }
} else {
    echo "Error en la solicitud.";
    exit;
}
