<?php
// views/cliente/nueva_proforma.php
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
AuthMiddleware::checkRole(['Cliente']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizador - SIGEF-RAMOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Roboto', sans-serif; }
        .bg-guinda { background-color: #6D0032 !important; color: white;}
        .text-guinda { color: #6D0032 !important; }
        .navbar-custom { background-color: #6D0032; }
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
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                    <div class="card-header bg-guinda py-3">
                        <h4 class="mb-0 fw-bold"><i class="bi bi-calculator me-2"></i>Cotizador de Servicios</h4>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="index.php?controller=cliente&action=generarProforma" method="POST" target="_blank">
                            <p class="text-muted mb-4">Seleccione los requerimientos base para generar una proforma en formato PDF instantaneamente.</p>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Categoria de Ataud / Urna</label>
                                <select class="form-select" name="tipo_ataud" required>
                                    <option value="Estandar">Ataud Estandar (Madera Prensada)</option>
                                    <option value="Madera Fina">Ataud de Madera Fina (Cedro/Caoba)</option>
                                    <option value="Lujo">Ataud de Lujo (Importado con acabados premium)</option>
                                    <option value="Urna Simple">Urna Simple (Tramite de Cremacion)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Servicios Adicionales</label>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="movilidad" name="movilidad" value="1">
                                    <label class="form-check-label" for="movilidad">Vehiculo Carroza + Traslado Local</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="capilla" name="capilla" value="1">
                                    <label class="form-check-label" for="capilla">Armado de Capilla Ardiente</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="cremacion" name="cremacion" value="1">
                                    <label class="form-check-label" for="cremacion">Servicio Integral de Cremacion</label>
                                </div>
                            </div>

                            <hr class="my-4">
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="index.php?controller=cliente" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Volver</a>
                                <button type="submit" class="btn btn-lg bg-guinda fw-bold"><i class="bi bi-file-earmark-pdf-fill me-2"></i> Generar Cotizacion</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
