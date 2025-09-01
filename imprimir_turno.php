<?php
include("autorizacion/auth.php"); // valida login y arranca sesión

// Verificar que tenga rol cliente
if ($_SESSION['rol'] !== 'cliente') {
    header("Location: index.php"); // lo mandamos al login
    exit();
}

include("templates/header.php");
require __DIR__ . '/vendor/autoload.php';
include_once 'bd/conexion.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// 2) Obtener id_turno de la URL
$id_turno = isset($_GET['id_turno']) ? (int)$_GET['id_turno'] : 0;
if ($id_turno <= 0) {
    die("Turno no especificado.");
}

// 3) Traer el turno
$sql = $conexion->prepare("
    SELECT 
        turnos.id AS id_turno,
        usuarios.nombre AS usuario,
        cedula,
        turnos.fecha_cita,
        turnos.hora_cita,
        servicios.nombre AS servicio,
        turnos.nota_adicional
    FROM turnos
    INNER JOIN servicios ON turnos.servicio_id = servicios.id
    INNER JOIN usuarios  ON turnos.usuario_id  = usuarios.id
    WHERE turnos.id = :id AND turnos.usuario_id = :id_usuario
    LIMIT 1
");
$sql->bindParam(':id', $id_turno, PDO::PARAM_INT);
$sql->bindParam(':id_usuario', $_SESSION['id'], PDO::PARAM_INT);
$sql->execute();
$turno = $sql->fetch(PDO::FETCH_OBJ);

if (!$turno) {
    die("Turno no encontrado o no pertenece a tu cuenta.");
}

// 4) Sanear datos
$paciente = htmlspecialchars($turno->usuario, ENT_QUOTES, 'UTF-8');
$cedula   = htmlspecialchars($turno->cedula, ENT_QUOTES, 'UTF-8');
$fecha    = htmlspecialchars($turno->fecha_cita, ENT_QUOTES, 'UTF-8');
$hora     = htmlspecialchars(substr((string)$turno->hora_cita, 0, 5), ENT_QUOTES, 'UTF-8');
$servicio = htmlspecialchars($turno->servicio, ENT_QUOTES, 'UTF-8');
$nota     = $turno->nota_adicional ? htmlspecialchars($turno->nota_adicional, ENT_QUOTES, 'UTF-8') : 'N/A';

// 5) Template HTML con mejor diseño
$html = <<<HTML
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Turno #{$id_turno}</title>
<style>
  @page { margin: 2cm; }
  body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 13px;
    color: #333;
  }
  .header {
    text-align: center;
    padding: 10px 0;
    margin-bottom: 20px;
    border-bottom: 2px solid #0066cc;
  }
  .header h1 {
    margin: 0;
    color: #0066cc;
    font-size: 20px;
  }
  .subtitle {
    text-align: center;
    font-size: 14px;
    margin-bottom: 20px;
    color: #555;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
  }
  td {
    border: 1px solid #ddd;
    padding: 10px;
  }
  .label {
    width: 180px;
    font-weight: bold;
    background: #f0f6ff;
    color: #004080;
  }
  .footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 11px;
    color: #888;
    border-top: 1px solid #ccc;
    padding-top: 5px;
  }
  .pagenum:before { content: counter(page) " / " counter(pages); }
</style>
</head>
<body>

  <div class="header">
    <h1>Comprobante de Turno</h1>
  </div>

  <p class="subtitle">Número de turno: <strong>#{$id_turno}</strong></p>

  <table>
    <tr><td class="label">Paciente</td><td>{$paciente}</td></tr>
    <tr><td class="label">Cédula</td><td>{$cedula}</td></tr>
    <tr><td class="label">Fecha</td><td>{$fecha}</td></tr>
    <tr><td class="label">Hora</td><td>{$hora}</td></tr>
    <tr><td class="label">Servicio</td><td>{$servicio}</td></tr>
    <tr><td class="label">Observaciones</td><td>{$nota}</td></tr>
    <tr><td class="label">Notas</td><td>Preséntese 15 minutos antes con su documento de identidad.</td></tr>
  </table>

  <div class="footer">
    Sistema de Turnos - Página <span class="pagenum"></span>
  </div>

</body>
</html>
HTML;

// 6) Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A5', 'portrait'); 
$dompdf->render();
$dompdf->stream("turno_{$id_turno}.pdf", ['Attachment' => false]);
