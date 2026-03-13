<?php
// views/cliente/dashboard.php
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
AuthMiddleware::checkRole(['Cliente']);

if (!isset($mis_servicios)) {
    $mis_servicios = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel - SIGEF-RAMOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Roboto', sans-serif; }
        .bg-guinda { background-color: #6D0032 !important; color: white;}
        .text-guinda { color: #6D0032 !important; }
        .navbar-custom { background-color: #6D0032; }
        .card-service { transition: transform 0.2s; border-radius: 12px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .card-service:hover { transform: translateY(-3px); }
        .hero { background: linear-gradient(135deg, #6D0032, #9E004A); color: white; border-radius: 15px; padding: 40px; margin-bottom: 30px;}
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?controller=cliente"><i class="bi bi-house-door-fill me-2"></i> Inicio Clientes</a>
            <div class="d-flex align-items-center">
                <span class="text-white-50 me-4"><i class="bi bi-person-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                <a href="index.php?controller=auth&action=logout" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="hero shadow-sm">
            <h2 class="fw-bold">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h2>
            <p class="mb-4 text-white-50">Gestiona tus servicios contratados o genera una nueva proforma para un familiar.</p>
            <a href="index.php?controller=cliente&action=nueva_proforma" class="btn btn-light text-guinda fw-bold px-4 py-2">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i> Generar Nueva Proforma
            </a>
        </div>

        <h4 class="fw-bold text-dark mb-4"><i class="bi bi-card-checklist text-guinda me-2"></i>Mis Servicios Contratados</h4>

        <div class="row g-4">
            <?php if (empty($mis_servicios)): ?>
                <div class="col-12 text-center py-5 bg-white rounded shadow-sm">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2 mb-0">No tienes servicios funerarios vinculados a tu DNI.</p>
                </div>
            <?php
else: ?>
                <?php foreach ($mis_servicios as $s): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-service h-100 border-top border-4 <?php echo($s['estado'] == 'finalizado') ? 'border-success' : 'border-warning'; ?>">
                        <div class="card-body">
                            <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($s['difunto_nombres'] . ' ' . $s['difunto_apellidos']); ?></h5>
                            <p class="text-muted small mb-3"><i class="bi bi-tag-fill me-1"></i> <?php echo htmlspecialchars($s['tipo_servicio']); ?></p>
                            
                            <ul class="list-unstyled small text-secondary mb-4">
                                <li class="mb-2"><i class="bi bi-geo-alt-fill me-2 text-guinda"></i> Sede: <?php echo htmlspecialchars($s['sede_nombre']); ?></li>
                                <li class="mb-2"><i class="bi bi-calendar-event-fill me-2 text-guinda"></i> Fecha Inicio: <?php echo date('d/m/Y', strtotime($s['fecha_servicio'])); ?></li>
                                <?php if ($s['estado'] == 'finalizado' && isset($s['fecha_finalizacion'])): ?>
                                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2 text-success"></i> Finalizado: <?php echo date('d/m/Y', strtotime($s['fecha_finalizacion'])); ?></li>
                                <?php
        endif; ?>
                            </ul>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <?php
        $badges = [
            'pendiente' => '<span class="badge bg-secondary p-2">Pendiente</span>',
            'en_preparacion' => '<span class="badge bg-warning text-dark p-2"><i class="bi bi-bandaid"></i> En Preparacion</span>',
            'en_traslado' => '<span class="badge bg-info text-dark p-2"><i class="bi bi-truck"></i> En Traslado</span>',
            'finalizado' => '<span class="badge bg-success p-2"><i class="bi bi-check-all"></i> Finalizado</span>'
        ];
        echo $badges[$s['estado']] ?? '<span class="badge bg-dark">' . $s['estado'] . '</span>';
?>
                                <span class="fw-bold text-guinda">S/. <?php echo number_format($s['precio'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
    endforeach; ?>
            <?php
endif; ?>
        </div>
    </div>
</body>
</html>
