<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
AuthMiddleware::checkRole('Vendedor');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Vendedor - SIGEF-RAMOS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-custom { background-color: #6D0032; color: #fff; }
        .bg-guinda { background-color: #6D0032 !important; color: white;}
        .text-guinda { color: #6D0032 !important; }
        .btn-guinda { background-color: #6D0032; color: white; border: none; transition: 0.3s; }
        .btn-guinda:hover { background-color: #520025; color: white; transform: translateY(-2px); }
        .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark header-custom">
        <div class="container">
            <a class="navbar-brand" href="#">SIGEF-RAMOS | Ventas</a>
            <div class="d-flex gx-2">
                <span class="navbar-text me-3 d-none d-md-block">Sede: <?php echo htmlspecialchars($_SESSION['sede']); ?></span>
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Admin'): ?>
                    <a href="index.php?controller=hub" class="btn btn-warning btn-sm me-2 fw-bold"><i class="bi bi-diagram-3-fill"></i> Volver al Hub</a>
                <?php
endif; ?>
                <a href="index.php?controller=auth&action=logout" class="btn btn-outline-light btn-sm">Cerrar Sesion</a>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row text-center mb-4">
            <div class="col-12">
                <h1 class="display-5 text-dark fw-bold">Panel de Ventas</h1>
                <p class="lead text-muted">Gestion de contratos y atencion a deudos</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <!-- Tarjeta Registro de Servicio (CU-06) -->
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-5 hover-lift">
                    <div class="card-body d-flex flex-column">
                        <div class="display-3 text-guinda mb-3 mt-auto">
                            <i class="bi bi-file-earmark-medical-fill"></i>
                        </div>
                        <h3 class="card-title fw-bold">Nuevo Servicio</h3>
                        <p class="card-text text-muted mb-4 fs-5">Registra un nuevo deudo, difunto y asigna un paquete de servicio funerario en 3 rapidos pasos.</p>
                        <a href="index.php?controller=vendedor&action=registro" class="btn btn-guinda btn-lg w-100 rounded-pill py-3 mt-auto fw-bold">
                            Iniciar Registro <i class="bi bi-arrow-right-circle ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Banner Opcional -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="alert alert-light border shadow-sm rounded-4 d-flex align-items-center" role="alert">
                    <i class="bi bi-info-circle-fill text-primary fs-3 me-3"></i>
                    <div>
                        <h6 class="alert-heading fw-bold mb-1">Aviso Importante</h6>
                        <span class="text-muted small">Recuerda coordinar con el area de Logistica (Operarios) inmediatamente despues de registrar un servicio que requiere traslado urgente.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
