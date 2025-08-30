<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include_once '../bd/conexion.php';
session_start();

// Seguridad: validar login
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: index.php");
    exit();
}

// Traer los registros
$sql = $conexion->prepare("SELECT turnos.id AS id_turno, fecha_cita, hora_cita, nota_adicional, estado, cedula, usuarios.nombre AS nombre_paciente, usuarios.email AS email_paciente 
    FROM turnos 
    INNER JOIN servicios ON turnos.servicio_id = servicios.id 
    INNER JOIN usuarios ON turnos.usuario_id = usuarios.id 
    WHERE turnos.servicio_id = 1");
$sql->execute();
$turnos = $sql->fetchAll(PDO::FETCH_OBJ);

// Configurar Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Armar HTML
$html = '
<h2 style="text-align:center;">Reporte de Turnos</h2>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr style="background:#3c8dbc; color:white;">
            <th>ID</th>
            <th>Paciente</th>
            <th>Email</th>
            <th>Cédula</th>
            <th>Fecha Cita</th>
            <th>Hora Cita</th>
            <th>Estado</th>
            <th>Nota Adicional</th>
        </tr>
    </thead>
    <tbody>';

foreach ($turnos as $t) {
    $html .= "<tr>
        <td>{$t->id_turno}</td>
        <td>{$t->nombre_paciente}</td>
        <td>{$t->email_paciente}</td>
        <td>{$t->cedula}</td>
        <td>{$t->fecha_cita}</td>
        <td>{$t->hora_cita}</td>
        <td>{$t->estado}</td>
        <td>" . ($t->nota_adicional ?: 'N/A') . "</td>
    </tr>";
}

$html .= '
    </tbody>
</table>
<p style="text-align:right; margin-top:20px;">Generado el ' . date("d/m/Y H:i") . '</p>
';

// Generar PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); // horizontal
$dompdf->render();
$dompdf->stream("turnosCG.pdf", ["Attachment" => false]); // false = abre en el navegador
exit;
