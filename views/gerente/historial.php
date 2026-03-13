<?php
if (!isset($_SESSION['id']) || !in_array($_SESSION['rol'], ['Gerente', 'Admin'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Contratos - SIGEF-RAMOS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
        }

        /* Sidebar similar to previous sections */
        .sidebar {
            height: 100vh;
            background-color: #2c3e50;
            color: #fff;
            position: fixed;
            width: 250px;
            top: 0;
            left: 0;
            padding-top: 20px;
            overflow-y: auto;
        }

        .sidebar .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }

        .sidebar ul.components {
            padding: 0;
            margin: 0;
        }

        .sidebar ul li a {
            padding: 15px 20px;
            display: block;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar ul li a:hover,
        .sidebar ul li.active > a {
            background-color: #1abc9c;
            color: #fff;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        .content-area {
            margin-left: 250px;
            padding: 40px;
        }

        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            border-radius: 8px;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .btn-guinda {
            background-color: #6D0032;
            color: white;
            border: none;
        }
        .btn-guinda:hover {
            background-color: #550027;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="bi bi-shield-lock-fill"></i> SIGEF-RAMOS</h4>
            <small class="text-white-50">Panel Gerencial</small>
        </div>
        <ul class="list-unstyled components">
            <li><a href="index.php?controller=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="index.php?controller=usuario"><i class="bi bi-people-fill"></i> Usuarios</a></li>
            <li><a href="index.php?controller=inventario"><i class="bi bi-box-seam"></i> Inventario</a></li>
            <li><a href="index.php?controller=sede"><i class="bi bi-building"></i> Sedes</a></li>
            <li><a href="index.php?controller=auth&action=logout" class="text-danger"><i class="bi bi-box-arrow-right"></i> Cerrar Sesion</a></li>
        </ul>
    </div>

    <!-- Content Area -->
    <div class="content-area">
        
        <!-- Header Navbar -->
        <div class="navbar">
            <h4 class="m-0 align-items-center d-flex"><i class="bi bi-journal-text me-2 text-primary"></i> Historial Completo de Contratos</h4>
            <div>
                <span class="text-muted border-end pe-3 me-3"><i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($_SESSION['rol']); ?></span>
                <span class="badge bg-secondary rounded-pill"><?php echo htmlspecialchars($_SESSION['sede']); ?></span>
            </div>
        </div>

        <!-- Tabla Historica Completa -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>N° Contrato</th>
                                <th>Fecha</th>
                                <th>Difunto</th>
                                <th>Paquete D. Servicio</th>
                                <th>Monto Total</th>
                                <th>Sede</th>
                                <th>Atendido Por</th>
                                <th>Estado</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($contratos)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">Aun no existen contratos registrados en el historial general.</td>
                                </tr>
                            <?php
else: ?>
                                <?php foreach ($contratos as $c): ?>
                                    <tr>
                                        <td class="fw-bold text-primary">N° <?php echo str_pad($c['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($c['fecha_servicio'])); ?></td>
                                        <td><?php echo htmlspecialchars($c['dif_nombres'] . ' ' . $c['dif_apellidos']); ?></td>
                                        <td><?php echo htmlspecialchars($c['tipo_servicio']); ?></td>
                                        <td class="fw-bold">S/ <?php echo number_format($c['precio'], 2); ?></td>
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($c['sede_nombre']); ?></span></td>
                                        <td><small class="text-muted"><i class="bi bi-person"></i> <?php echo htmlspecialchars($c['vendedor_nombre']); ?></small></td>
                                        <td>
                                            <?php
        $badge_class = 'bg-secondary';
        $estado_texto = ucfirst(str_replace('_', ' ', $c['estado']));
        switch ($c['estado']) {
            case 'pendiente':
                $badge_class = 'bg-warning text-dark';
                break;
            case 'en_preparacion':
                $badge_class = 'bg-info text-dark';
                break;
            case 'en_traslado':
                $badge_class = 'bg-primary';
                break;
            case 'finalizado':
                $badge_class = 'bg-success';
                break;
            case 'cancelado':
                $badge_class = 'bg-danger';
                break;
        }
?>
                                            <span class="badge <?php echo $badge_class; ?>"><?php echo $estado_texto; ?></span>
                                        </td>
                                        <td>
                                            <a href="index.php?controller=reporte&action=generarContrato&id=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-danger" target="_blank" title="Descargar PDF">
                                                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                                            </a>
                                        </td>
                                    </tr>
                                <?php
    endforeach; ?>
                            <?php
endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
