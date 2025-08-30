<?php
include_once "templates/header.php"; // este archivo es el que  ya tiene session_start()

// Verificar si el usuario está logueado
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: index.php");
    exit();
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
    
    .alert {
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 3px;
            font-size: 14px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
</style>
<div class="page-header">
    <h1 class="page-title">Actualizacion de Contraseña</h1>
</div>
<!-- Formulario de agendamiento -->
<div class="card">
    <div class="card-header">
        <h3>Recuerde que su contraseña debe ser segura</h3>
    </div>
    <div class="card-body">
        <form id="appointmentForm" method="POST" action="controladores/actualizarPassword.php">
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

            <?php if (isset($_SESSION['exito'])) : ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= $_SESSION['exito']; ?>
                </div>
                <?php unset($_SESSION['exito']); ?>
            <?php endif; ?>
            <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id']; ?>">
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group required">
                        <label for="patientName">Contraseña Actual</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="patientName" name="password" required>
                        </div>
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group required">
                        <label for="patientId">Contraseña Nueva</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="patientId" name="password_nueva" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-pen"></i> Actualizar
                </button>
            </div>
        </form>
    </div>
</div>


<?php include_once "templates/footer.php"; ?>