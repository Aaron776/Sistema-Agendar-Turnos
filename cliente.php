<?php
include("autorizacion/auth.php"); // valida login y arranca sesión

// Verificar que tenga rol cliente
if ($_SESSION['rol'] !== 'cliente') {
    header("Location: index.php"); // lo mandamos al login
    exit();
}


include("templates/header.php");
include_once 'bd/conexion.php';

// Traer los servicios de la base de datos
$sql = $conexion->prepare("SELECT * FROM servicios");
$sql->execute();
$servicios = $sql->fetchAll(PDO::FETCH_OBJ);
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

    /* Formularios */
    .form-group {
        margin-bottom: 15px;
        position: relative;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: var(--text);
    }

    .form-group.required label::after {
        content: " *";
        color: var(--danger);
    }

    .input-group {
        position: relative;
    }

    .input-group-prepend {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eee;
        border: 1px solid var(--border);
        border-right: none;
        border-radius: 3px 0 0 3px;
        color: #777;
    }

    .form-control {
        width: 100%;
        padding: 10px 10px 10px 45px;
        border: 1px solid var(--border);
        border-radius: 3px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(60, 141, 188, 0.2);
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .form-col {
        flex: 1;
        padding: 0 10px;
        min-width: 250px;
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

        .form-col {
            flex: 100%;
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


    /* Alertas */
    .alert-danger {
        display: block; /* antes estaba none */
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 10px 15px;
        border-radius: 3px;
        margin-bottom: 15px;
    }
</style>
<div class="page-header">
    <h1 class="page-title">Agendamiento de Turnos</h1>
</div>
<!-- Formulario de agendamiento -->
<div class="card">
    <div class="card-header">
        <h3>Nuevo Turno</h3>
    </div>
    <div class="card-body">
        <form id="appointmentForm" method="POST" action="controladores/registroTurno.php">
            <?php if (isset($_SESSION['errores'])) : ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($_SESSION['errores'] as $error) : ?>
                            <li><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errores']); ?>
            <?php endif; ?>
            <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id']; ?>">
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group required">
                        <label for="patientName">Nombre del Paciente</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="patientName" name="nombre" value="<?php echo $_SESSION['nombre']; ?>" placeholder="Nombre completo" required readonly>
                        </div>
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group required">
                        <label for="patientId">Identificación</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <i class="fas fa-id-card"></i>
                            </span>
                            <input type="text" class="form-control" minlength="10" maxlength="10" id="patientId" name="cedula" placeholder="Número de identificación" value="<?php echo $_SESSION['cedula']; ?>" required readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <div class="form-group required">
                        <label for="appointmentDate">Fecha del Turno</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <i class="fas fa-calendar"></i>
                            </span>
                            <input type="date" class="form-control" id="appointmentDate" name="fecha_cita" required>
                        </div>
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group required">
                        <label for="appointmentTime">Hora del Turno</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <i class="fas fa-clock"></i>
                            </span>
                            <input type="time" class="form-control" id="appointmentTime" min="09:00" max="18:00" name="hora_cita" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <div class="form-group required">
                        <label for="serviceType">Tipo de Servicio</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <i class="fas fa-stethoscope"></i>
                            </span>
                            <select class="form-control" id="serviceType" required name="servicio">
                                <option value="">Seleccione un servicio</option>
                                <?php
                                foreach ($servicios as $item) {
                                    echo '<option value="' . $item->id . '">' . $item->nombre . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label for="notes">Notas Adicionales</label>
                        <textarea class="form-control" name="nota_adicional" id="notes" rows="3" maxlength="100" placeholder="Observaciones o comentarios adicionales"></textarea>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-calendar-plus"></i> Agendar Turno
                </button>
            </div>
        </form>
    </div>
</div>
<?php include 'templates/footer.php'; ?>