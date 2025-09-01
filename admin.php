<?php
include("autorizacion/auth.php"); // valida login y arranca sesión

// Verificar que tenga rol admin
if ($_SESSION['rol'] !== 'admin') {
    header("Location: index.php"); // lo mandamos al login
    exit();
}


include("templates/header.php");
include_once 'controladores/admin.php';
?>

<style>
    /* Contenido */
    .content {
        padding: 20px;
    }

    .page-header {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
    }

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
        transition: transform 0.3s;
    }

    .info-box:hover {
        transform: translateY(-5px);
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

    .bg-info {
        background-color: var(--info);
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

    /* Bienvenida */
    .welcome-section {
        background: white;
        border-radius: 5px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: var(--shadow);
        text-align: center;
        background: linear-gradient(135deg, #3c8dbc 0%, #367fa9 100%);
        color: white;
    }

    .welcome-title {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .welcome-subtitle {
        font-size: 16px;
        opacity: 0.9;
        margin-bottom: 20px;
    }

    .welcome-actions {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }

    /* Acciones rápidas */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .action-card {
        background: white;
        border-radius: 5px;
        padding: 20px;
        text-align: center;
        box-shadow: var(--shadow);
        transition: all 0.3s;
        border-top: 3px solid var(--primary);
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .action-icon {
        font-size: 40px;
        margin-bottom: 15px;
        color: var(--primary);
    }

    .action-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .action-description {
        font-size: 14px;
        color: #666;
        margin-bottom: 15px;
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

    .btn-outline-light {
        background: transparent;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
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

        .welcome-actions {
            flex-direction: column;
        }

        .quick-actions {
            grid-template-columns: 1fr;
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
</style>
<div class="welcome-section fade-in">
    <h1 class="welcome-title">¡Bienvenido, <?php echo $_SESSION['nombre']; ?>!</h1>
    <p class="welcome-subtitle">Panel de administración del Sistema de Turnos</p>
</div>

<div class="page-header">
    <h1>Resumen del Sistema</h1>
</div>

<!-- Tarjetas de información -->
<div class="row">
    <div class="col">
        <div class="info-box">
            <div class="info-box-icon bg-primary">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="info-box-content">
                <span class="info-box-text">Turnos Hoy: </span>
                <span class="info-box-number"><?php echo $cantidadTurnosHoy; ?></span>
                <p><?php echo $cantidadTurnosRealizados; ?> completados, <?php echo $cantidadTurnosPendientes; ?> pendientes</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="info-box">
            <div class="info-box-icon bg-success">
                <i class="fas fa-user-injured"></i>
            </div>
            <div class="info-box-content">
                <span class="info-box-text">Pacientes Registrados en el Sistema</span>
                <p class="info-box-number"><?php echo $cantidadPacientes; ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Tabla con los turnos de hoy -->
<div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
        Detalle de Turnos de Hoy
    </div>
    <div class="card-body">
        <?php if (!empty($turnosHoy)) : ?>
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Paciente</th>
                        <th>Hora</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($turnosHoy as $item) : ?>
                        <tr>
                            <td><?php echo $item->id; ?></td>
                            <td><?php echo htmlspecialchars($item->paciente); ?></td>
                            <td><?php echo date("H:i", strtotime($item->hora_cita)); ?></td>
                            <td>
                                <?php if ($item->estado == 'pendiente') : ?>
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                <?php elseif ($item->estado == 'atendido') : ?>
                                    <span class="badge bg-success">Atendido</span>
                                <?php else : ?>
                                    <span class="badge bg-secondary">Otro</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div class="alert alert-info text-center">
                No hay turnos agendados para hoy.
            </div>
        <?php endif; ?>
    </div>
</div>


<!-- Acciones rápidas -->
<div class="page-header">
    <h1>Acciones Rápidas</h1>
</div>

<div class="quick-actions">
    <div class="action-card">
        <div class="action-icon">
            <i class="fas fa-search"></i>
        </div>
        <div class="action-title">Buscar Turnos</div>
        <div class="action-description">Buscar turnos por fecha o paciente</div>
        <button class="btn btn-primary" onclick="window.location.href='buscar_turnos.php'">
            <i class="fas fa-search"></i> Buscar
        </button>
    </div>

    <div class="action-card">
        <div class="action-icon">
            <i class="fas fa-file-medical"></i>
        </div>
        <div class="action-title">Generar Reporte</div>
        <div class="action-description">Crear reportes de actividad del sistema</div>
        <button class="btn btn-primary" onclick="window.location.href='generar_reporte.php'">
            <i class="fas fa-download"></i> Generar
        </button>
    </div>

    <div class="action-card">
        <div class="action-icon">
            <i class="fas fa-cog"></i>
        </div>
        <div class="action-title">Configuración</div>
        <div class="action-description">Ajustar configuraciones del sistema</div>
        <button class="btn btn-primary" onclick="window.location.href='configuracion.php'">
            <i class="fas fa-cog"></i> Configurar
        </button>
    </div>
</div>

<?php include 'templates/footer.php'; ?>