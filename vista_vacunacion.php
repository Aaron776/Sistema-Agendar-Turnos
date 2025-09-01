<?php
include("autorizacion/auth.php"); // valida login y arranca sesión

// Verificar que tenga rol admin
if ($_SESSION['rol'] !== 'admin') {
    header("Location: index.php"); // lo mandamos al login
    exit();
}

include("templates/header.php");
include_once 'bd/conexion.php';


// Actualizar el estado de los turnos pendientes para cambiar su estado a "realizado" una vez que paso la fecha y la hora de la cita independientemente si el pacinte fue o no a la cita
$conexion->exec(" UPDATE turnos SET estado = 'realizado' WHERE estado = 'pendiente'  AND STR_TO_DATE(CONCAT(fecha_cita, ' ', hora_cita), '%Y-%m-%d %H:%i:%s') < NOW()");


// Traer los servicios de la base de datos
$sql = $conexion->prepare("SELECT turnos.id AS id_turno, fecha_cita, hora_cita, nota_adicional,estado,cedula,estado,usuarios.nombre AS nombre_paciente, usuarios.email AS email_paciente FROM turnos INNER JOIN servicios ON turnos.servicio_id = servicios.id INNER JOIN usuarios ON turnos.usuario_id = usuarios.id WHERE turnos.servicio_id = 4");
$sql->execute();
$turnos = $sql->fetchAll(PDO::FETCH_OBJ);
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
        overflow-x: auto;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        font-weight: 500;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        animation: fadeIn 0.5s ease-in-out;
        box-shadow: var(--shadow);
    }

    .alert-success {
        background-color: rgba(0, 166, 90, 0.15);
        color: var(--success);
        border: 1px solid var(--success);
    }

    .alert-danger {
        background-color: rgba(221, 75, 57, 0.15);
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    .alert-info {
        background-color: rgba(0, 192, 239, 0.15);
        color: var(--info);
        border: 1px solid var(--info);
    }

    .alert i {
        font-size: 18px;
    }


    /* Tabla estilizada */
    .styled-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    }

    .styled-table thead tr {
        background-color: var(--primary);
        color: white;
        text-align: left;
    }

    .styled-table th,
    .styled-table td {
        padding: 15px;
        position: relative;
        transition: all 0.3s;
    }

    .styled-table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 14px;
        cursor: pointer;
        user-select: none;
    }

    .styled-table th:hover {
        background-color: #367fa9;
    }

    .styled-table th i {
        margin-left: 5px;
        font-size: 12px;
        opacity: 0.7;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #eee;
        transition: all 0.3s;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f9f9f9;
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid var(--primary);
    }

    .styled-table tbody tr:hover {
        background-color: rgba(60, 141, 188, 0.05);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .styled-table tbody td {
        position: relative;
    }

    .styled-table tbody td:hover::before {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background-color: rgba(60, 141, 188, 0.03);
        z-index: -1;
    }

    /* Badges */
    .badge {
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
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

    .badge-info {
        background: var(--info);
        color: white;
    }

    /* Acciones */
    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn {
        padding: 6px 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }

    .btn i {
        margin-right: 4px;
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

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        background: #c23321;
    }

    .btn-info {
        background: var(--info);
        color: white;
    }

    .btn-info:hover {
        background: #00a7d0;
    }

    /* Botones de exportación */
    .btn-export {
        font-size: 14px;
        padding: 8px 14px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .btn-pdf {
        background: #d9534f;
        color: white;
    }

    .btn-pdf:hover {
        background: #c9302c;
    }

    .btn-excel {
        background: #5cb85c;
        color: white;
    }

    .btn-excel:hover {
        background: #449d44;
    }


    /* Búsqueda y filtros */
    .table-controls {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .search-box {
        position: relative;
        min-width: 250px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1px solid var(--border);
        border-radius: 3px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(60, 141, 188, 0.2);
    }

    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #777;
    }

    .table-info {
        display: flex;
        align-items: center;
        color: #666;
        font-size: 14px;
    }

    /* Paginación */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        gap: 5px;
    }

    .page-item {
        display: inline-block;
    }

    .page-link {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 3px;
        color: var(--primary);
        text-decoration: none;
        transition: all 0.2s;
    }

    .page-link:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .page-item.active .page-link {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
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

        .table-controls {
            flex-direction: column;
        }

        .search-box {
            width: 100%;
        }

        .styled-table {
            min-width: 800px;
        }
    }

    /* Efectos y animaciones */
    .fade-in {
        animation: fadeIn 0.5s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .highlight {
        animation: highlight 2s ease;
    }

    @keyframes highlight {
        0% {
            background-color: rgba(60, 141, 188, 0.3);
        }

        100% {
            background-color: transparent;
        }
    }

    /* Loader para tabla */
    .table-loading {
        position: relative;
    }

    .table-loading::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .table-loading::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 30px;
        height: 30px;
        margin: -15px 0 0 -15px;
        border: 3px solid var(--border);
        border-top: 3px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        z-index: 100;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<div class="page-header">
    <h1>Tabla de Turnos <small>Vacunacion</small></h1>
</div>

<div class="card fade-in">
    <div class="card-header">
        <h3>Lista de turnos</h3>
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['mensaje'])) : ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php
                echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
                ?>
            </div>
        <?php endif; ?>
        <!-- Controles de tabla -->
        <div class="table-controls">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar turnos...">
            </div>
            <a class="btn btn-export btn-pdf" href="registrosPDF/imprimir_registro_va_pdf.php" target="_blank">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
            <a class="btn btn-export btn-excel" href="registrosExcel/imprimir_registro_va_excel.php">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <div class="table-info">
                Mostrando <span id="itemsCount"> 10</span> de 50 registros
            </div>
        </div>

        <!-- Tabla estilizada -->
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID <i class="fas fa-sort"></i></th>
                    <th>Paciente <i class="fas fa-sort"></i></th>
                    <th>Email <i class="fas fa-sort"></i></th>
                    <th>Cedula <i class="fas fa-sort"></i></th>
                    <th>Fecha de la Cita <i class="fas fa-sort"></i></th>
                    <th>Hora de la Cita <i class="fas fa-sort"></i></th>
                    <th>Estado <i class="fas fa-sort"></i></th>
                    <th>Nota Adicional <i class="fas fa-sort"></i></th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($turnos as $item) { ?>
                    <tr>
                        <td>#<?php echo $item->id_turno; ?></td>
                        <td><?php echo $item->nombre_paciente; ?></td>
                        <td><?php echo $item->cedula; ?></td>
                        <td><?php echo $item->email_paciente; ?></td>
                        <td><?php echo $item->fecha_cita; ?></td>
                        <td><?php echo $item->hora_cita; ?></td>
                        <td>
                            <?php if ($item->estado == "pendiente") { ?>
                                <span class="badge badge-warning">
                                    <?php echo $item->estado; ?>
                                </span>
                            <?php } elseif ($item->estado == "realizado") { ?>
                                <span class="badge badge-success">
                                    <?php echo $item->estado; ?>
                                </span>
                            <?php } ?>
                        </td>
                        <?php if ($item->nota_adicional != "") { ?>
                            <td><?php echo $item->nota_adicional; ?></td>
                        <?php } else { ?>
                            <td>N/A</td>
                        <?php } ?>
                        <td>
                            <?php if ($item->estado == "realizado") { ?>
                                <form action="controladores/eliminarTurno.php?id_turno=<?php echo $item->id_turno; ?>" method="POST">
                                    <div class="action-buttons">
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </div>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="pagination">
            <li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-left"></i></a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">4</a></li>
            <li class="page-item"><a class="page-link" href="#">5</a></li>
            <li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-right"></i></a></li>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle sidebar en móviles
        const toggleSidebar = document.querySelector('.toggle-sidebar');
        const sidebar = document.querySelector('.sidebar');
        const contentWrapper = document.querySelector('.content-wrapper');

        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            contentWrapper.classList.toggle('sidebar-active');
        });

        // Efecto de carga para la tabla
        const table = document.querySelector('.styled-table');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
            row.classList.add('fade-in');
        });

        // Funcionalidad de búsqueda
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            let visibleItems = 0;

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let found = false;

                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(searchText)) {
                        found = true;
                    }
                });

                if (found) {
                    row.style.display = '';
                    visibleItems++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('itemsCount').textContent = visibleItems;
        });

        // Funcionalidad de ordenamiento
        const headers = document.querySelectorAll('th');
        headers.forEach(header => {
            header.addEventListener('click', function() {
                const columnIndex = Array.from(headers).indexOf(this);
                const tbody = table.querySelector('tbody');
                const sortedRows = Array.from(rows);

                sortedRows.sort((a, b) => {
                    const aValue = a.cells[columnIndex].textContent;
                    const bValue = b.cells[columnIndex].textContent;

                    if (!isNaN(aValue) && !isNaN(bValue)) {
                        return aValue - bValue;
                    }

                    return aValue.localeCompare(bValue);
                });

                // Limpiar y reordenar filas
                while (tbody.firstChild) {
                    tbody.removeChild(tbody.firstChild);
                }

                sortedRows.forEach(row => {
                    tbody.appendChild(row);
                });
            });
        });

        // Efecto de highlight en filas al hacer clic
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                if (!e.target.closest('.action-buttons')) {
                    this.classList.toggle('highlight');
                }
            });
        });
    });
</script>