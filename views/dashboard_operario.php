<?php
// views/dashboard_operario.php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
// Auth check is also in controller, but this acts as an extra safety measure
AuthMiddleware::checkRole(['Operario', 'Gerente']);

// El Controller inyecta la variable $servicios con los datos del dia
if (!isset($servicios)) {
    $servicios = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logistica Operaria - SIGEF-RAMOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Roboto', sans-serif; overflow-x: hidden; }
        .bg-guinda { background-color: #6D0032 !important; color: white;}
        .text-guinda { color: #6D0032 !important; }
        .navbar-custom { background-color: #6D0032; }
        
        .card-service { transition: transform 0.2s; border-radius: 12px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 5px solid transparent; }
        .card-service:hover { transform: translateY(-3px); }
        
        /* Status Colors */
        .status-pendiente { border-left-color: #6c757d; }
        .status-en_preparacion { border-left-color: #ffc107; }
        .status-en_traslado { border-left-color: #0dcaf0; }
        
        .badge-sede-1 { background-color: #0d6efd; } /* Ilo - Azul */
        .badge-sede-2 { background-color: #fd7e14; } /* Moquegua - Naranja */
    </style>
</head>
<body>

    <!-- Navbar Minimalista para el Operario -->
    <nav class="navbar navbar-dark navbar-custom shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-truck me-2"></i> Logistica SIGEF-RAMOS</a>
            <div class="d-flex align-items-center gx-2">
                <span class="text-white-50 me-3 d-none d-md-block"><i class="bi bi-person-gear me-1"></i> <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Admin'): ?>
                    <a href="index.php?controller=hub" class="btn btn-warning btn-sm me-2 fw-bold"><i class="bi bi-diagram-3-fill"></i> Volver al Hub</a>
                <?php
endif; ?>
                <a href="index.php?controller=auth&action=logout" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right d-none d-md-inline"></i> Salir</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row mb-4 align-items-center">
            <div class="col-md-8">
                <h3 class="fw-bold text-dark mb-1"><i class="bi bi-list-task text-guinda me-2"></i>Tablero de Servicios Activos</h3>
                <p class="text-muted mb-0">Listado de difuntos requiriendo preparacion o traslado.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button onclick="location.reload();" class="btn btn-outline-secondary shadow-sm"><i class="bi bi-arrow-clockwise me-1"></i> Refrescar Panel</button>
            </div>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Estado actualizado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
endif; ?>

        <!-- Listado de Tarjetas -->
        <div class="row g-4">
            <?php if (empty($servicios)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-emoji-smile text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No hay servicios pendientes por el momento.</h5>
                </div>
            <?php
else: ?>
                <?php foreach ($servicios as $s): ?>
                
                <div class="col-12">
                    <div class="card card-service status-<?php echo htmlspecialchars($s['estado']); ?>">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                
                                <!-- Info del Difunto y Geolocalizacion -->
                                <div class="col-md-5 mb-3 mb-md-0">
                                    <h5 class="fw-bold text-dark mb-1">
                                        <?php echo htmlspecialchars($s['difunto_nombres'] . ' ' . $s['difunto_apellidos']); ?>
                                    </h5>
                                    <p class="text-muted mb-2 small"><i class="bi bi-info-square me-1"></i> Paquete: <?php echo htmlspecialchars($s['tipo_servicio']); ?></p>
                                    
                                    <!-- Geolocalizacion Simbolica -->
                                    <span class="badge badge-sede-<?php echo $s['sede_id']; ?> rounded-pill mb-1">
                                        <i class="bi bi-geo-alt-fill me-1"></i> Sede <?php echo htmlspecialchars($s['sede_nombre']); ?>
                                    </span>
                                    
                                    <?php if (!empty($s['descripcion'])): ?>
                                        <div class="text-secondary small mt-2 fst-italic"><i class="bi bi-chat-left-text me-1"></i> "<?php echo htmlspecialchars($s['descripcion']); ?>"</div>
                                    <?php
        endif; ?>
                                </div>

                                <!-- Estado Actual -->
                                <div class="col-md-2 mb-3 mb-md-0 text-md-center">
                                    <span class="d-block text-muted small text-uppercase fw-bold mb-1">Estado</span>
                                    <?php
        $badges = [
            'pendiente' => '<span class="badge bg-secondary">Pendiente</span>',
            'en_preparacion' => '<span class="badge bg-warning text-dark"><i class="bi bi-bandaid"></i> En Preparacion</span>',
            'en_traslado' => '<span class="badge bg-info text-dark"><i class="bi bi-truck"></i> En Traslado</span>'
        ];
        echo $badges[$s['estado']] ?? '<span class="badge bg-dark">' . $s['estado'] . '</span>';
?>
                                </div>

                                <!-- Acciones Rapidas de Cambio de Estado -->
                                <div class="col-md-5 text-md-end border-md-start ps-md-4">
                                    <form action="index.php?controller=operario&action=cambiarEstado" method="POST" class="d-flex flex-wrap justify-content-md-end gap-2 form-estado">
                                        <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                        <input type="hidden" name="estado" class="input-estado" value="">
                                        
                                        <?php if ($s['estado'] == 'pendiente'): ?>
                                            <button type="button" class="btn btn-warning flex-grow-1 flex-md-grow-0 fw-bold shadow-sm btn-cambio-estado" data-estado="en_preparacion">
                                                <i class="bi bi-bandaid"></i> Preparar
                                            </button>
                                            <button type="button" class="btn btn-info flex-grow-1 flex-md-grow-0 fw-bold shadow-sm btn-cambio-estado" data-estado="en_traslado">
                                                <i class="bi bi-cursor-fill"></i> Trasladar
                                            </button>
                                        <?php
        elseif ($s['estado'] == 'en_preparacion'): ?>
                                            <button type="button" class="btn btn-info flex-grow-1 flex-md-grow-0 fw-bold shadow-sm btn-cambio-estado" data-estado="en_traslado">
                                                <i class="bi bi-cursor-fill"></i> Cargar a Carroza
                                            </button>
                                        <?php
        elseif ($s['estado'] == 'en_traslado'): ?>
                                            <button type="button" class="btn btn-warning flex-grow-1 flex-md-grow-0 fw-bold shadow-sm btn-cambio-estado" data-estado="en_preparacion">
                                                <i class="bi bi-bandaid"></i> Devolver a Prep.
                                            </button>
                                        <?php
        endif; ?>
                                        
                                        <!-- Finalizar: Lo saca de la lista activa del Operario -->
                                        <button type="button" class="btn btn-success flex-grow-1 flex-md-grow-0 fw-bold shadow-sm ms-md-2 btn-finalizar" data-estado="finalizado" data-nombre="<?php echo htmlspecialchars($s['difunto_nombres'] . ' ' . $s['difunto_apellidos']); ?>" data-servicio="<?php echo htmlspecialchars($s['tipo_servicio']); ?>" title="Marcar como Completo">
                                            <i class="bi bi-check2-all"></i> Finalizar
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
                <?php
    endforeach; ?>
            <?php
endif; ?>
        </div>
        <!-- Tarjetas Finalizadas (Para Deshacer Errores) -->
        <div class="row mt-5 mb-3 align-items-center">
            <div class="col-12">
                <h4 class="fw-bold text-success mb-1"><i class="bi bi-check-circle-fill me-2"></i>Ultimos Servicios Finalizados</h4>
                <p class="text-muted mb-0 small">Historial de servicios completados en esta sede.</p>
            </div>
        </div>

        <div class="row g-3">
            <?php if (empty($servicios_finalizados)): ?>
                <div class="col-12 text-center py-3">
                    <p class="text-muted small">No hay servicios completados registrados.</p>
                </div>
            <?php
else: ?>
                <?php
    $count = 0;
    foreach ($servicios_finalizados as $sf):
        $count++;
        if ($count == 4) {
            echo '<div class="col-12 text-center mt-3"><button class="btn btn-sm btn-outline-secondary rounded-pill px-4" type="button" data-bs-toggle="collapse" data-bs-target="#moreFinalizados" aria-expanded="false" aria-controls="moreFinalizados">Ver mas finalizados <i class="bi bi-chevron-down ms-1"></i></button></div>';
            echo '<div class="collapse w-100" id="moreFinalizados"><div class="row g-3 mt-1">';
        }
?>
                <div class="col-12">
                    <div class="card card-service border-left-color bg-light opacity-75 hover-lift" style="border-left-color: #198754;">
                        <div class="card-body py-3 px-4">
                            <div class="row align-items-center">
                                <div class="col-md-7 mb-2 mb-md-0">
                                    <h6 class="fw-bold text-dark mb-1 text-decoration-line-through">
                                        <?php echo htmlspecialchars($sf['difunto_nombres'] . ' ' . $sf['difunto_apellidos']); ?>
                                    </h6>
                                    <div class="text-muted small mb-1">
                                        <i class="bi bi-info-square me-1"></i> <?php echo htmlspecialchars($sf['tipo_servicio']); ?>
                                        <span class="ms-2"><i class="bi bi-clock-history me-1"></i> Cerrado: <?php echo date('d/m/Y H:i', strtotime($sf['fecha_finalizacion'] ?? $sf['fecha_servicio'])); ?></span>
                                    </div>
                                    <span class="badge badge-sede-<?php echo $sf['sede_id']; ?> rounded-pill" style="font-size: 0.65em;">
                                        Sede <?php echo htmlspecialchars($sf['sede_nombre']); ?>
                                    </span>
                                </div>
                                <div class="col-md-5 text-md-end">
                                    <form action="index.php?controller=operario&action=cambiarEstado" method="POST" class="d-inline form-revertir">
                                        <input type="hidden" name="id" value="<?php echo $sf['id']; ?>">
                                        <input type="hidden" name="estado" value="pendiente">
                                        <button type="button" class="btn btn-sm btn-outline-danger shadow-sm btn-revertir" data-nombre="<?php echo htmlspecialchars($sf['difunto_nombres'] . ' ' . $sf['difunto_apellidos']); ?>" data-servicio="<?php echo htmlspecialchars($sf['tipo_servicio']); ?>" title="Revertir este servicio a la bandeja activa">
                                            <i class="bi bi-arrow-counterclockwise"></i> Deshacer Finalizacion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
    endforeach; ?>
                <?php if ($count >= 4) {
        echo '</div></div>';
    }?>
            <?php
endif; ?>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.btn-cambio-estado').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                form.querySelector('.input-estado').value = this.dataset.estado;
                form.submit();
            });
        });

        document.querySelectorAll('.btn-finalizar').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                form.querySelector('.input-estado').value = this.dataset.estado;
                
                const nombre = this.dataset.nombre;
                const servicio = this.dataset.servicio;
                
                Swal.fire({
                    title: '¿Confirmar Finalizacion?',
                    html: `Finalizar <strong>${servicio}</strong> para <strong>${nombre}</strong>.<br><br>El servicio funerario saldra de esta lista y se marcara como completado.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Finalizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        document.querySelectorAll('.btn-revertir').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                const nombre = this.dataset.nombre;
                const servicio = this.dataset.servicio;
                
                Swal.fire({
                    title: '¿Deshacer Finalizacion?',
                    html: `Deshacer la finalizacion de <strong>${servicio}</strong> para <strong>${nombre}</strong>.<br><br>El servicio volvera a la parte superior como 'Pendiente'.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Si, Deshacer',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>
</html>
