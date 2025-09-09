<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include_once '../bd/conexion.php';
session_start();

// Seguridad: validar login
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: index.php");
    exit();
}

// Traer los registros
$sql = $conexion->prepare("SELECT turnos.id AS id_turno, fecha_cita, hora_cita, nota_adicional, estado, usuarios.cedula AS cedula, usuarios.nombre AS nombre_paciente, usuarios.email AS email_paciente 
    FROM turnos 
    INNER JOIN servicios ON turnos.servicio_id = servicios.id 
    INNER JOIN usuarios ON turnos.usuario_id = usuarios.id 
    WHERE turnos.servicio_id = 5");
$sql->execute();
$turnos = $sql->fetchAll(PDO::FETCH_OBJ);

// Crear Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Paciente');
$sheet->setCellValue('C1', 'Email');
$sheet->setCellValue('D1', 'Cédula');
$sheet->setCellValue('E1', 'Fecha Cita');
$sheet->setCellValue('F1', 'Hora Cita');
$sheet->setCellValue('G1', 'Estado');
$sheet->setCellValue('H1', 'Nota Adicional');

// Poner en negrita encabezados
$sheet->getStyle('A1:H1')->getFont()->setBold(true);

// Insertar registros
$fila = 2;
foreach ($turnos as $t) {
    $sheet->setCellValue('A' . $fila, $t->id_turno);
    $sheet->setCellValue('B' . $fila, $t->nombre_paciente);
    $sheet->setCellValue('C' . $fila, $t->email_paciente);
    $sheet->setCellValue('D' . $fila, $t->cedula);
    $sheet->setCellValue('E' . $fila, $t->fecha_cita);
    $sheet->setCellValue('F' . $fila, $t->hora_cita);
    $sheet->setCellValue('G' . $fila, $t->estado);
    $sheet->setCellValue('H' . $fila, $t->nota_adicional ?: 'N/A');
    $fila++;
}

// Ajustar ancho automático
foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Descargar el archivo
$writer = new Xlsx($spreadsheet);

// Cabeceras para descargar
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="turnosEM.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
