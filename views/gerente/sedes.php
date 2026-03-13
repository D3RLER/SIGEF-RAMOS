<?php
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
// Check is done in Controller too.
AuthMiddleware::checkRole(['Gerente', 'Admin']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Sedes - SIGEF-RAMOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Roboto', sans-serif; overflow-x: hidden; }
        .wrapper { display: flex; width: 100%; align-items: stretch; }
        #sidebar { min-width: 250px; max-width: 250px; min-height: 100vh; background-color: #212529; color: #fff; }
        #sidebar .sidebar-header { padding: 20px; background: #1a1e21; border-bottom: 2px solid #6D0032; }
        #sidebar ul.components { padding: 20px 0; }
        #sidebar ul li a { padding: 12px 20px; font-size: 1.1em; display: block; color: #adb5bd; text-decoration: none; transition: 0.2s; }
        #sidebar ul li a:hover, #sidebar ul li.active > a { color: #fff; background: #6D0032; border-left: 4px solid #fff; }
        #sidebar ul li a i { margin-right: 10px; }
        #content { width: 100%; padding: 20px; }
        .bg-guinda { background-color: #6D0032 !important; color: white; }
        .text-guinda { color: #6D0032 !important; }
        .btn-guinda { background-color: #6D0032; color: white; border: none; }
        .btn-guinda:hover { background-color: #520025; color: white; }
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
                <li><a href="index.php?controller=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="index.php?controller=usuario"><i class="bi bi-people-fill"></i> Usuarios</a></li>
                <li><a href="index.php?controller=inventario"><i class="bi bi-box-seam"></i> Inventario</a></li>
                <li class="active"><a href="index.php?controller=sede"><i class="bi bi-building"></i> Sedes</a></li>
                <li class="mt-5 border-top border-secondary pt-3">
                    <a href="index.php?controller=auth&action=logout" class="text-danger"><i class="bi bi-box-arrow-left"></i> Cerrar Sesion</a>
                </li>
            </ul>
        </nav>

        <!-- Cabecera Content -->
        <div id="content">
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 shadow-sm rounded">
                <h4 class="mb-0 text-dark fw-bold"><i class="bi bi-building text-guinda me-2"></i>Gestion de Sedes Regionales</h4>
                <button class="btn btn-guinda" data-bs-toggle="modal" data-bs-target="#sedeModal" onclick="nuevaSede()"><i class="bi bi-plus-circle me-1"></i> Nueva Sede</button>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> Operacion completada con exito.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php
endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] == 'constraint'): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> No se puede eliminar la sede porque existen registros (servicios/usuarios) dependientes de ella.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php
endif; ?>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Nombre de Sede</th>
                                    <th>Direccion</th>
                                    <th>Telefono</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sedes)): ?>
                                    <?php foreach ($sedes as $s): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-muted">#<?php echo $s['id']; ?></td>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($s['nombre']); ?></td>
                                        <td class="text-muted"><i class="bi bi-geo-alt-fill me-1 small"></i><?php echo htmlspecialchars($s['direccion'] ?? 'N/D'); ?></td>
                                        <td class="text-muted"><i class="bi bi-telephone-fill me-1 small"></i><?php echo htmlspecialchars($s['telefono'] ?? 'N/D'); ?></td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-outline-primary" 
                                                onclick="editarSede(<?php echo $s['id']; ?>, '<?php echo addslashes($s['nombre']); ?>', '<?php echo addslashes($s['direccion']); ?>', '<?php echo addslashes($s['telefono']); ?>')" 
                                                data-bs-toggle="modal" data-bs-target="#sedeModal">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <a href="index.php?controller=sede&action=eliminar&id=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar esta sede? Se bloqueara si tiene contratos asociados.')">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
    endforeach; ?>
                                <?php
else: ?>
                                    <tr><td colspan="5" class="text-center py-4 text-muted">No existen sedes registradas.</td></tr>
                                <?php
endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Sede -->
    <div class="modal fade" id="sedeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-guinda text-white">
                    <h5 class="modal-title fw-bold" id="modalTitle"><i class="bi bi-building-add me-2"></i>Registrar Sede</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php?controller=sede&action=guardar" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="sede_id">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre de Instalacion <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" id="sede_nombre" required placeholder="Ej: Sede Tacna">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Direccion Fiscal / Ubicacion</label>
                            <input type="text" class="form-control" name="direccion" id="sede_direccion" placeholder="Av. Principal 123...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telefono Fijo / Celular</label>
                            <input type="text" class="form-control" name="telefono" id="sede_telefono" placeholder="(053) 48XXXX">
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-guinda"><i class="bi bi-save me-1"></i> Guardar Sede</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function nuevaSede() {
            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-building-add me-2"></i>Registrar Nueva Sede';
            document.getElementById('sede_id').value = '';
            document.getElementById('sede_nombre').value = '';
            document.getElementById('sede_direccion').value = '';
            document.getElementById('sede_telefono').value = '';
        }

        function editarSede(id, nombre, direccion, telefono) {
            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar Sede Regional';
            document.getElementById('sede_id').value = id;
            document.getElementById('sede_nombre').value = nombre;
            document.getElementById('sede_direccion').value = direccion;
            document.getElementById('sede_telefono').value = telefono;
        }
    </script>
</body>
</html>
