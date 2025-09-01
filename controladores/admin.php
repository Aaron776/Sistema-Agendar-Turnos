<?php
include_once 'bd/conexion.php';

// Traer la cantidad de  usuarios cuyo rol se de paciente
$sql = $conexion->prepare("SELECT COUNT(*) AS cantidad FROM usuarios WHERE rol = 'cliente'");
$sql->execute();
$resultado = $sql->fetch(PDO::FETCH_OBJ);
$cantidadPacientes = $resultado->cantidad;

// Traer todos los turnos de la fecha de hoy 
$sql = $conexion->prepare("SELECT COUNT(*) as total FROM turnos WHERE fecha_cita = CURDATE()");
$sql->execute();
$cantidadTurnosHoy = $sql->fetch(PDO::FETCH_OBJ)->total;

// Listado de turnos de hoy
$sql = $conexion->prepare("SELECT t.id, t.fecha_cita, t.hora_cita, t.estado, u.nombre AS paciente FROM turnos t INNER JOIN usuarios u ON t.usuario_id = u.id WHERE t.fecha_cita = CURDATE() ORDER BY t.hora_cita ASC");
$sql->execute();
$turnosHoy = $sql->fetchAll(PDO::FETCH_OBJ);



// Traer todo los turnos que esten con el campo de estado pendiente
$sql=$conexion->prepare("SELECT COUNT(*) AS cantidad FROM turnos WHERE estado='pendiente'");
$sql->execute();
$resultado = $sql->fetch(PDO::FETCH_OBJ);
$cantidadTurnosPendientes = $resultado->cantidad;

// Traer todo los turnos que esten con el campo de estado realizado
$sql=$conexion->prepare("SELECT COUNT(*) AS cantidad FROM turnos WHERE estado='realizado'");
$sql->execute();
$resultado = $sql->fetch(PDO::FETCH_OBJ);
$cantidadTurnosRealizados = $resultado->cantidad;


?>