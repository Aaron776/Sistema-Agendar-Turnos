<?php
// Comprobar el estado actual de la sesión
if (session_status() === PHP_SESSION_NONE) { // Si no hay ninguna sesión activa
    session_start(); // Inicia una nueva sesión o reanuda la existente
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema para Agendar Turnos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3c8dbc;
            --secondary: #222d32;
            --light: #ecf0f5;
            --success: #00a65a;
            --info: #00c0ef;
            --warning: #f39c12;
            --danger: #dd4b39;
            --text: #444;
            --text-light: #b8c7ce;
            --border: #d2d6de;
            --shadow: 0 3px 6px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light);
            color: var(--text);
            overflow-x: hidden;
        }

        /* Layout principal */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 230px;
            background-color: var(--secondary);
            color: var(--text-light);
            transition: all 0.3s ease;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            box-shadow: var(--shadow);
        }

        .sidebar-header {
            padding: 15px;
            background-color: #367fa9;
            text-align: center;
            border-bottom: 1px solid #4b646f;
        }

        .sidebar-header h3 {
            color: white;
            font-weight: 600;
        }

        .sidebar-menu {
            padding: 10px 0;
        }

        .sidebar-menu ul {
            list-style: none;
        }

        .sidebar-menu li {
            position: relative;
        }

        .sidebar-menu a {
            color: var(--text-light);
            text-decoration: none;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }

        .sidebar-menu a:hover {
            background-color: #1e282c;
            color: white;
        }

        .sidebar-menu a.active {
            background-color: #1a2226;
            border-left: 3px solid var(--primary);
            color: white;
        }

        .sidebar-menu i {
            width: 25px;
            font-size: 18px;
        }

        /* Menú desplegable */
        .treeview-menu {
            display: none;
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: #2c3b41;
        }

        .treeview-menu.active {
            display: block;
        }

        .treeview-menu li a {
            padding-left: 45px;
            font-size: 14px;
        }

        .treeview-menu li a i {
            font-size: 12px;
        }

        .menu-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            transition: all 0.3s;
        }

        .treeview.active .menu-toggle {
            transform: translateY(-50%) rotate(90deg);
        }

        /* Contenido principal */
        .content-wrapper {
            flex: 1;
            margin-left: 230px;
            transition: all 0.3s ease;
        }

        /* Header */
        .main-header {
            background-color: var(--primary);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
        }

        .toggle-sidebar {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 3px;
            transition: background 0.3s;
        }

        .toggle-sidebar:hover {
            background: rgba(0,0,0,0.1);
        }

        .header-icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-icon {
            position: relative;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            transition: background 0.3s;
        }

        .header-icon:hover {
            background: rgba(0,0,0,0.1);
        }

        .icon-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Menú de usuario */
        .user-menu {
            position: relative;
            display: inline-block;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .user-avatar:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .user-dropdown {
            position: absolute;
            top: 50px;
            right: 0;
            width: 200px;
            background: white;
            border-radius: 3px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
        }

        .user-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-header {
            padding: 15px;
            text-align: center;
            background: var(--primary);
            color: white;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
        }

        .user-dropdown-header h4 {
            margin-bottom: 5px;
        }

        .user-dropdown-header p {
            font-size: 13px;
            opacity: 0.8;
        }

        .user-dropdown-menu {
            padding: 10px 0;
        }

        .user-dropdown-menu a {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: var(--text);
            text-decoration: none;
            transition: all 0.2s;
        }

        .user-dropdown-menu a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .user-dropdown-menu a:hover {
            background: #f9f9f9;
            color: var(--primary);
        }

        .user-dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 5px 0;
        }

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

        .bg-primary { background-color: var(--primary); }
        .bg-success { background-color: var(--success); }
        .bg-warning { background-color: var(--warning); }
        .bg-danger { background-color: var(--danger); }

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

        /* Tablas */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 10px;
            border: 1px solid var(--border);
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
            font-weight: 600;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #f1f1f1;
        }

        /* Progress bars */
        .progress {
            height: 20px;
            background: #f4f4f4;
            border-radius: 5px;
            overflow: hidden;
            margin: 10px 0;
        }

        .progress-bar {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 12px;
        }

        /* Botones */
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
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
            0% { box-shadow: 0 0 0 0 rgba(60, 141, 188, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(60, 141, 188, 0); }
            100% { box-shadow: 0 0 0 0 rgba(60, 141, 188, 0); }
        }

        .fade-in {
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>Sistema de Turnos</h3>
            </div>
            <nav class="sidebar-menu">
                <ul>
                    
                    <?php if($_SESSION['rol'] == 'admin') { ?>
                        <li><a href="admin.php"><i class="fas fa-table"></i> Dashboard</a></li>
                        <li><a href="vista_consulta_general.php"><i class="fas fa-user-md"></i> Turnos para Consulta General</a></li>
                        <li><a href="vista_emergencia.php"><i class="fas fa-ambulance"></i> Turnos para Emergencias</a></li>
                        <li><a href="vista_vacunacion.php"><i class="fas fa-vial"></i> Turnos para Vacunacion</a></li>
                        <li><a href="vista_examen_medico.php"><i class="fas fa-stethoscope"></i> Turnos para Examenes Medicos</a></li>
                        <li><a href="vista_controles.php"><i class="fas fa-user-md"></i> Turnos para Controles de Rutina</a></li>
                    <?php } ?>
                    <?php if($_SESSION['rol'] == 'cliente') { ?>
                        <li><a href="cliente.php"><i class="fas fa-table"></i> Turnos</a></li>
                    <?php } ?>
                </ul>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <div class="content-wrapper">
            <!-- Header -->
            <header class="main-header">
                <button class="toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-icons">
                    <div class="user-menu">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-dropdown">
                            <div class="user-dropdown-header">
                                <h4><?php echo $_SESSION['nombre']; ?></h4>
                                <p><?php echo $_SESSION['rol']; ?></p>
                            </div>
                            <div class="user-dropdown-menu">
                                <a href="cambiar_password.php">
                                    <i class="fas fa-key"></i> Cambiar Contraseña
                                </a>
                                <div class="user-dropdown-divider"></div>
                                <a href="controladores/logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Contenido -->
            <main class="content">