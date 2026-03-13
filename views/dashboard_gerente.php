<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/DashboardController.php';

AuthMiddleware::checkRole(['Gerente', 'Admin']);

$dashboardController = new DashboardController();

// Manejo de Filtros
$sede_id = isset($_GET['sede_id']) && $_GET['sede_id'] !== '' ? (int)$_GET['sede_id'] : null;
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

$data = $dashboardController->getDashboardData($sede_id, $fecha_inicio, $fecha_fin);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gerente - SIGEF-RAMOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f4f6f9; font-family: 'Roboto', sans-serif; overflow-x: hidden; }
        
        /* Layout Structure */
        .wrapper { display: flex; width: 100%; align-items: stretch; }
        
        /* Sidebar Styling */
        #sidebar { min-width: 250px; max-width: 250px; min-height: 100vh; background-color: #212529; color: #fff; transition: all 0.3s; }
        #sidebar .sidebar-header { padding: 20px; background: #1a1e21; border-bottom: 2px solid #6D0032; }
        #sidebar ul.components { padding: 20px 0; }
        #sidebar ul li a { padding: 12px 20px; font-size: 1.1em; display: block; color: #adb5bd; text-decoration: none; transition: 0.2s; }
        #sidebar ul li a:hover, #sidebar ul li.active > a { color: #fff; background: #6D0032; border-left: 4px solid #fff; }
        #sidebar ul li a i { margin-right: 10px; }
        
        /* Main Content */
        #content { width: 100%; transition: all 0.3s; }
        
        /* Navbar */
        .header-custom { background-color: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 15px 20px; }
        
        /* Colors & Cards */
        .bg-guinda { background-color: #6D0032 !important; }
        .text-guinda { color: #6D0032 !important; }
        .btn-guinda { background-color: #6D0032; color: white; border: none; }
        .btn-guinda:hover { background-color: #520025; color: white; }
        .card-stat { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s; }
        .card-stat:hover { transform: translateY(-5px); }
        .icon-stat { font-size: 2.5rem; opacity: 0.8; }
        .critical-stock-bg { background-color: #ffe5e5 !important; border-left: 4px solid #dc3545; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header text-center">
                <h4 class="mb-0 fw-bold">SIGEF-RAMOS</h4>
                <small class="text-white-50">Panel Gerencial</small>
            </div>
            <ul class="list-unstyled components">
                <li class="active">
                    <a href="index.php?controller=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
                </li>
                <li>
                    <a href="index.php?controller=usuario"><i class="bi bi-people-fill"></i> Usuarios</a>
                </li>
                <li>
                    <a href="index.php?controller=inventario"><i class="bi bi-box-seam"></i> Inventario</a>
                </li>
                <li>
                    <a href="index.php?controller=sede"><i class="bi bi-building"></i> Sedes</a>
                </li>
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Admin'): ?>
                <li class="mt-4 pb-2 border-bottom border-secondary">
                    <a href="index.php?controller=hub" class="text-warning fw-bold"><i class="bi bi-diagram-3-fill"></i> Volver al Hub Inicial</a>
                </li>
                <?php
endif; ?>
                <li class="<?php echo(isset($_SESSION['rol']) && $_SESSION['rol'] === 'Admin') ? 'mt-3' : 'mt-5 border-top border-secondary pt-3'; ?>">
                    <a href="index.php?controller=auth&action=logout" class="text-danger"><i class="bi bi-box-arrow-left"></i> Cerrar Sesion</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg header-custom mb-4">
                <div class="container-fluid">
                    <span class="navbar-brand fw-bold text-dark"><i class="bi bi-bar-chart-fill text-guinda me-2"></i> Auditoria y Reportes (CU-04)</span>
                    <div class="d-flex align-items-center">
                        <div class="text-end me-3">
                            <span class="d-block text-dark fw-bold" style="font-size: 0.9rem;">Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                            <span class="badge bg-secondary">Viendo: <?php echo htmlspecialchars($data['sede_nombre_activa']); ?></span>
                        </div>
                        <i class="bi bi-person-circle fs-3 text-muted"></i>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4 pb-5">
                <!-- Filtros -->
                <div class="card bg-white border-0 shadow-sm rounded-3 mb-4 p-3">
                    <form id="filterForm" method="GET" action="index.php" class="row g-3 align-items-end">
                        <input type="hidden" name="controller" value="dashboard">
                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Admin'): ?>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold small">Filtrar por Sede</label>
                            <select name="sede_id" class="form-select" onchange="document.getElementById('filterForm').submit();">
                                <option value="">Todas las Sedes</option>
                                <option value="1" <?php echo $sede_id === 1 ? 'selected' : ''; ?>>Ilo</option>
                                <option value="2" <?php echo $sede_id === 2 ? 'selected' : ''; ?>>Moquegua</option>
                            </select>
                        </div>
                        <?php
endif; ?>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold small">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="<?php echo htmlspecialchars($fecha_inicio ?? ''); ?>" onchange="document.getElementById('filterForm').submit();">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold small">Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" value="<?php echo htmlspecialchars($fecha_fin ?? ''); ?>" onchange="document.getElementById('filterForm').submit();">
                        </div>
                        <!-- Boton Oculto para que no interrumpa, pero se somete con JS -->
                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Admin'): ?>
                        <div class="col-md-3 d-none">
                            <button type="submit" class="btn btn-guinda w-100">Aplicar Filtros</button>
                        </div>
                        <?php
endif; ?>
                        <div class="col-md-3">
                            <a href="views/exportar_servicios.php?<?php echo http_build_query($_GET); ?>" class="btn btn-success w-100" target="_blank"><i class="bi bi-file-earmark-excel-fill me-2"></i>Exportar CSV</a>
                        </div>
                    </form>
                </div>

                <!-- Tarjetas de Metricas (4 Tarjetas) -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="card card-stat bg-primary text-white h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-white-50 text-uppercase fw-bold mb-1">Ventas Mes</h6>
                                    <h2 class="mb-0 fw-bold"><?php echo $data['ventas_mes']; ?></h2>
                                </div>
                                <i class="bi bi-briefcase-fill icon-stat"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="card card-stat bg-success text-white h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-white-50 text-uppercase fw-bold mb-1">Servicios Activos</h6>
                                    <h2 class="mb-0 fw-bold"><?php echo $data['servicios_activos']; ?></h2>
                                </div>
                                <i class="bi bi-activity icon-stat"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="card card-stat <?php echo count($data['stock_critico']) > 0 ? 'critical-stock-bg text-dark' : 'bg-info text-white'; ?> h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="card-title text-uppercase fw-bold mb-0 <?php echo count($data['stock_critico']) > 0 ? 'text-danger' : 'text-white-50'; ?>" style="font-size: 0.8rem;">Stock Critico</h6>
                                    <i class="bi bi-box-seam-fill fs-3 <?php echo count($data['stock_critico']) > 0 ? 'text-danger' : 'opacity-50'; ?>"></i>
                                </div>
                                <?php if (count($data['stock_critico']) > 0): ?>
                                    <ul class="list-unstyled mb-0 small" style="max-height: 60px; overflow-y: auto;">
                                        <?php foreach ($data['stock_critico'] as $item): ?>
                                            <li class="border-bottom border-danger border-opacity-25 pb-1 mb-1 fw-bold text-truncate">
                                                <i class="bi bi-exclamation-circle-fill text-danger me-1"></i> 
                                                <?php echo htmlspecialchars($item['producto']); ?> 
                                                <span class="badge bg-danger float-end"><?php echo $item['stock']; ?></span>
                                            </li>
                                        <?php
    endforeach; ?>
                                    </ul>
                                <?php
else: ?>
                                    <h4 class="mb-0 fw-bold mt-2"><i class="bi bi-check-circle-fill me-1"></i> Optimo</h4>
                                <?php
endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="card card-stat bg-secondary text-white h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-white-50 text-uppercase fw-bold mb-1">Empleados Activos</h6>
                                    <h2 class="mb-0 fw-bold"><?php echo $data['usuarios_conectados']; ?></h2>
                                </div>
                                <i class="bi bi-people-fill icon-stat"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Grafico de Tendencias -->
                    <div class="col-lg-7 mb-4">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="mb-0 text-guinda fw-bold"><i class="bi bi-graph-up text-guinda me-2"></i>Tendencia de Servicios vs Mes</h6>
                            </div>
                            <div class="card-body pb-0">
                                <canvas id="tendenciaChart" height="110"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Ultimos Contratos -->
                    <div class="col-lg-5 mb-4">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-dark fw-bold"><i class="bi bi-clock-history text-secondary me-2"></i>Ultimos 5 Contratos</h6>
                                <button onclick="window.print()" class="btn btn-sm btn-outline-secondary" title="Imprimir Liquidacion"><i class="bi bi-printer"></i></button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-borderless align-middle mb-0">
                                        <thead class="table-light text-muted small">
                                            <tr>
                                                <th class="ps-3">Servicio</th>
                                                <th>Vendedor/Sede</th>
                                                <th class="text-end">Monto</th>
                                                <th class="text-center pe-3">PDF</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($data['ultimos_contratos'])): ?>
                                                <tr><td colspan="4" class="text-center text-muted py-3">No hay contratos recientes</td></tr>
                                            <?php
else: ?>
                                                <?php foreach ($data['ultimos_contratos'] as $contrato): ?>
                                                <tr>
                                                    <td class="ps-3 text-nowrap">
                                                        <span class="fw-bold text-dark d-block text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($contrato['tipo_servicio']); ?></span>
                                                        <small class="text-muted d-block"><?php echo htmlspecialchars($contrato['difunto_apellidos']); ?></small>
                                                        <small><span class="badge bg-<?php echo $contrato['estado'] == 'finalizado' ? 'success' : ($contrato['estado'] == 'en_proceso' ? 'primary' : 'warning text-dark'); ?>"><?php echo $contrato['estado']; ?></span></small>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-light rounded-circle p-2 me-2 text-primary"><i class="bi bi-person-badge"></i></div>
                                                            <div>
                                                                <span class="d-block fw-semibold small"><?php echo htmlspecialchars($contrato['vendedor_nombre'] ?? 'Sin asignar'); ?></span>
                                                                <span class="badge bg-secondary" style="font-size: 0.65em;"><?php echo htmlspecialchars($contrato['sede_nombre']); ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end fw-bold text-success">S/ <?php echo number_format($contrato['precio'], 2); ?></td>
                                                    <td class="text-center pe-3">
                                                        <a href="../index.php?controller=reporte&action=generarContrato&id=<?php echo $contrato['id']; ?>" class="btn btn-sm btn-outline-danger" target="_blank" title="Imprimir Contrato PDF"><i class="bi bi-file-earmark-pdf-fill"></i></a>
                                                    </td>
                                                </tr>
                                                <?php
    endforeach; ?>
                                            <?php
endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3 text-center">
                                    <a href="index.php?controller=reporte" class="btn btn-guinda w-100 fw-bold px-4 py-2" style="border-radius: 8px;">
                                        Ver Historial de Contratos <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inicializacion del Grafico de Chart.js -->
    <script>
        // Data inyectada desde PHP
        const chartLabels = <?php echo json_encode($data['chart_labels']); ?>;
        const chartData = <?php echo json_encode($data['chart_data']); ?>;

        const ctx = document.getElementById('tendenciaChart').getContext('2d');
        const tendenciaChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels.length > 0 ? chartLabels : ['Sin Datos'],
                datasets: [{
                    label: 'Cantidad de Servicios',
                    data: chartData.length > 0 ? chartData : [0],
                    backgroundColor: '#6D0032',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    </script>
</body>
</html>
