<?php
include("autorizacion/auth.php"); // valida login y arranca sesión

// Verificar que tenga rol cliente
if ($_SESSION['rol'] !== 'cliente') {
    header("Location: index.php"); // lo mandamos al login
    exit();
}


include("templates/header.php");
include_once 'bd/conexion.php';

// Aqui esto guardando el id del ultimo turno registrado en la base de datos que fue mandado por la URL desde mi controlador registroTurno.php
$id_turno = $_GET['id_turno'];

// Traer los atributos o datos del ultimo turno de la base de datos 
$sql = $conexion->prepare("SELECT usuarios.nombre as usuario, usuarios.cedula AS cedula,fecha_cita,hora_cita,servicios.nombre as servicio,nota_adicional FROM turnos INNER JOIN servicios ON turnos.servicio_id = servicios.id INNER JOIN usuarios ON turnos.usuario_id = usuarios.id WHERE turnos.id = :id AND usuario_id = :id_usuario");
$sql->bindParam(':id', $id_turno);
$sql->bindParam(':id_usuario', $_SESSION['id']);
$sql->execute();
$turno = $sql->fetch(PDO::FETCH_OBJ);

if ($turno == false) {
    die("Turno no encontrado o no pertenece a tu cuenta.");
}
?>
<style>
    /* Tarjetas */
    .card {
        background: white;
        border-radius: 3px;
        box-shadow: var(--shadow);
        margin-bottom: 20px;
        border-top: 3px solid var(--primary);
    }

    .card-header {
        padding: 15px;
        border-bottom: 1px solid var(--border);
        font-weight: 600;
        color: var(--text);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-body {
        padding: 15px;
    }

    /* Grid de tarjetas */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .col {
        flex: 1;
        padding: 10px;
        min-width: 300px;
    }

    /* Tarjetas de información */
    .info-box {
        display: flex;
        background: white;
        border-radius: 3px;
        box-shadow: var(--shadow);
        margin-bottom: 15px;
        min-height: 80px;
    }

    .info-box-icon {
        width: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        color: white;
        border-top-left-radius: 3px;
        border-bottom-left-radius: 3px;
    }

    .bg-primary {
        background-color: var(--primary);
    }

    .bg-success {
        background-color: var(--success);
    }

    .bg-warning {
        background-color: var(--warning);
    }

    .bg-danger {
        background-color: var(--danger);
    }

    .info-box-content {
        flex: 1;
        padding: 10px;
    }

    .info-box-text {
        font-size: 14px;
        margin-bottom: 5px;
    }

    .info-box-number {
        font-size: 22px;
        font-weight: 600;
    }

    /* Botones */
    .btn {
        padding: 10px 15px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn i {
        margin-right: 5px;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: #367fa9;
    }

    .btn-success {
        background: var(--success);
        color: white;
    }

    .btn-success:hover {
        background: #008d4c;
    }

    .btn-info {
        background: var(--info);
        color: white;
    }

    .btn-info:hover {
        background: #00a7d0;
    }

    /* Footer */
    .main-footer {
        background: white;
        padding: 15px;
        text-align: center;
        border-top: 1px solid var(--border);
        margin-top: 20px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            margin-left: -230px;
        }

        .sidebar.active {
            margin-left: 0;
        }

        .content-wrapper {
            margin-left: 0;
        }

        .content-wrapper.sidebar-active {
            margin-left: 230px;
        }
    }

    /* Efectos y animaciones */
    .pulse {
        display: inline-block;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(60, 141, 188, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(60, 141, 188, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(60, 141, 188, 0);
        }
    }

    .fade-in {
        animation: fadeIn 0.5s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Badges */
    .badge {
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-primary {
        background: var(--primary);
        color: white;
    }

    .badge-success {
        background: var(--success);
        color: white;
    }

    .badge-warning {
        background: var(--warning);
        color: white;
    }

    .badge-danger {
        background: var(--danger);
        color: white;
    }

    /* Comprobante de turno */
    .confirmation-card {
        text-align: center;
        padding: 20px;
    }

    .confirmation-icon {
        font-size: 80px;
        color: var(--success);
        margin-bottom: 20px;
    }

    .confirmation-title {
        font-size: 24px;
        color: var(--success);
        margin-bottom: 10px;
    }

    .confirmation-subtitle {
        font-size: 16px;
        color: var(--text);
        margin-bottom: 30px;
    }

    .appointment-details {
        background: #f9f9f9;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: left;
    }

    .detail-row {
        display: flex;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }

    .detail-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .detail-label {
        font-weight: 600;
        width: 200px;
        color: var(--text);
    }

    .detail-value {
        flex: 1;
        color: #666;
    }


    @media print {
        body * {
            visibility: hidden;
        }

        .ticket,
        .ticket * {
            visibility: visible;
        }

        .ticket {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
        }
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }

        .detail-row {
            flex-direction: column;
        }

        .detail-label {
            width: 100%;
            margin-bottom: 5px;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">Confirmación de Cita</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="confirmation-card">
            <div class="confirmation-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="confirmation-title">¡Cita Agendada Exitosamente!</h2>
            <p class="confirmation-subtitle">Su turno ha sido registrado en nuestro sistema. A continuación los detalles:</p>

            <div class="appointment-details">
                <div class="detail-row">
                    <span class="detail-label">Paciente:</span>
                    <span class="detail-value"><?php echo $turno->usuario; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Cedula de Identidad:</span>
                    <span class="detail-value"><?php echo $turno->cedula; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Fecha de la Cita:</span>
                    <span class="detail-value"><?php echo $turno->fecha_cita; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Cedula de Identidad:</span>
                    <span class="detail-value"><?php echo $turno->cedula; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Hora de la Cita:</span>
                    <span class="detail-value"><?php echo $turno->hora_cita; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Servicio:</span>
                    <span class="detail-value"><?php echo $turno->servicio; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Dirección:</span>
                    <span class="detail-value">Av. Principal #123, Centro Médico AdminLTE, Consultorio 5B</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Observaciones:</span>
                    <?php if (empty($turno->nota_adicional)) { ?>
                        <span class="detail-value">N/A</span>
                    <?php } else { ?>
                        <span class="detail-value"><?php echo $turno->nota_adicional; ?></span>
                    <?php } ?>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Notas:</span>
                    <span class="detail-value">Llegar 15 minutos antes con documento de identidad</span>
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn btn-primary" onclick="window.location.href='cliente.php'">
                    <i class="fas fa-calendar-plus"></i> Agendar Nueva Cita
                </button>
                <a class="btn btn-info" href="imprimir_turno.php?id_turno=<?php echo $id_turno; ?>" target="_blank">
                    <i class="fas fa-print"></i> Imprimir Turno
                </a>

            </div>
        </div>
    </div>
</div>


<?php include 'templates/footer.php'; ?>