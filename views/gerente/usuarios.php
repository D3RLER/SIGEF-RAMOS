<?php
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
AuthMiddleware::checkRole(['Gerente', 'Admin']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Usuarios - SIGEF-RAMOS</title>
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
        
        .badge-rol-Gerente { background-color: #dc3545; }
        .badge-rol-Vendedor { background-color: #0d6efd; }
        .badge-rol-Operario { background-color: #198754; }
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
                <li class="active"><a href="index.php?controller=usuario"><i class="bi bi-people-fill"></i> Usuarios</a></li>
                <li><a href="index.php?controller=inventario"><i class="bi bi-box-seam"></i> Inventario</a></li>
                <li><a href="index.php?controller=sede"><i class="bi bi-building"></i> Sedes</a></li>
                <li class="mt-5 border-top border-secondary pt-3">
                    <a href="index.php?controller=auth&action=logout" class="text-danger"><i class="bi bi-box-arrow-left"></i> Cerrar Sesion</a>
                </li>
            </ul>
        </nav>

        <!-- Cabecera Content -->
        <div id="content">
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 shadow-sm rounded">
                <h4 class="mb-0 text-dark fw-bold"><i class="bi bi-people-fill text-guinda me-2"></i>Gestion de Personal y Usuarios</h4>
                <button class="btn btn-guinda" data-bs-toggle="modal" data-bs-target="#usuarioModal" onclick="nuevoUsuario()"><i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario</button>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> Operacion completada con exito.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php
endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                    <?php
    if ($_GET['error'] == 'duplicado')
        echo "El nombre de usuario ya existe intente con otro.";
    if ($_GET['error'] == 'propio')
        echo "No puedes eliminar tu propia cuenta de gerente actual.";
?>
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
                                    <th>Nombre Completo</th>
                                    <th>Username</th>
                                    <th>Rol Funcion</th>
                                    <th>Sede Asignada</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($usuarios)): ?>
                                    <?php foreach ($usuarios as $u): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-muted">#<?php echo $u['id']; ?></td>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($u['nombre']); ?></td>
                                        <td class="text-muted">@<?php echo htmlspecialchars($u['username']); ?></td>
                                        <td><span class="badge badge-rol-<?php echo $u['rol']; ?>"><?php echo $u['rol']; ?></span></td>
                                        <td class="text-muted"><i class="bi bi-geo-alt-fill me-1 small text-guinda"></i><?php echo htmlspecialchars($u['sede_nombre'] ?? 'Global/Matriz'); ?></td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-outline-primary" 
                                                onclick="editarUsuario(<?php echo $u['id']; ?>, '<?php echo addslashes($u['nombre']); ?>', '<?php echo addslashes($u['username']); ?>', '<?php echo addslashes($u['rol']); ?>', '<?php echo $u['sede_id'] ?? ''; ?>')" 
                                                data-bs-toggle="modal" data-bs-target="#usuarioModal">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <?php if ($_SESSION['id'] != $u['id']): ?>
                                            <button class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                onclick="confirmarEliminacion(<?php echo $u['id']; ?>, '<?php echo addslashes($u['nombre']); ?>')" 
                                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                <i class="bi bi-person-x-fill"></i>
                                            </button>
                                            <?php
        endif; ?>
                                        </td>
                                    </tr>
                                    <?php
    endforeach; ?>
                                <?php
else: ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No existen usuarios registrados.</td></tr>
                                <?php
endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Usuario -->
    <div class="modal fade" id="usuarioModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-guinda text-white">
                    <h5 class="modal-title fw-bold" id="modalTitle"><i class="bi bi-person-plus-fill me-2"></i>Registrar Empleado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php?controller=usuario&action=guardar" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="usuario_id">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" id="usuario_nombre" required placeholder="Juan Perez">
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="username" id="usuario_username" required placeholder="jperez">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Contrasena</label>
                                <input type="password" class="form-control" name="password" id="usuario_password" placeholder="Solo para cambiar">
                                <div class="form-text small" id="password_help">Dejalo en blanco si no deseas cambiarla.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Rol de Acceso <span class="text-danger">*</span></label>
                                <select class="form-select" name="rol" id="usuario_rol" required>
                                    <option value="Gerente">Gerente</option>
                                    <option value="Vendedor">Vendedor</option>
                                    <option value="Operario">Operario</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Sede Asignada</label>
                                <select class="form-select" name="sede_id" id="usuario_sede">
                                    <option value="">(Global / Ninguna)</option>
                                    <?php if (!empty($sedes)): ?>
                                        <?php foreach ($sedes as $s): ?>
                                            <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['nombre']); ?></option>
                                        <?php
    endforeach; ?>
                                    <?php
endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-guinda"><i class="bi bi-save me-1"></i> Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Eliminacion -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Eliminacion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-trash-fill text-danger" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-1 fs-5">¿Esta seguro de eliminar permanentemente al usuario?</p>
                    <p class="fw-bold fs-4 text-dark" id="deleteUsuarioNombre"></p>
                    <p class="text-muted small">ID Sistema: #<span id="deleteUsuarioIdTxt"></span></p>
                    
                    <form id="deleteForm" action="index.php" method="GET">
                        <input type="hidden" name="controller" value="usuario">
                        <input type="hidden" name="action" value="eliminar">
                        <input type="hidden" name="id" id="deleteUsuarioIdInput">
                    </form>
                </div>
                <div class="modal-footer bg-light justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger px-4" onclick="document.getElementById('deleteForm').submit()"><i class="bi bi-person-x-fill me-2"></i>Si, Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarEliminacion(id, nombre) {
            document.getElementById('deleteUsuarioNombre').textContent = nombre;
            document.getElementById('deleteUsuarioIdTxt').textContent = id;
            document.getElementById('deleteUsuarioIdInput').value = id;
        }
        function nuevoUsuario() {
            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-person-plus-fill me-2"></i>Registrar Nuevo Empleado';
            document.getElementById('usuario_id').value = '';
            document.getElementById('usuario_nombre').value = '';
            document.getElementById('usuario_username').value = '';
            document.getElementById('usuario_password').value = '';
            document.getElementById('usuario_password').required = true;
            document.getElementById('password_help').style.display = 'none';
            document.getElementById('usuario_rol').value = 'Vendedor';
            document.getElementById('usuario_sede').value = '';
        }

        function editarUsuario(id, nombre, username, rol, sede) {
            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar Accesos de Empleado';
            document.getElementById('usuario_id').value = id;
            document.getElementById('usuario_nombre').value = nombre;
            document.getElementById('usuario_username').value = username;
            document.getElementById('usuario_password').value = ''; // Empty so it doesn't get updated unless typed
            document.getElementById('usuario_password').required = false;
            document.getElementById('password_help').style.display = 'block';
            document.getElementById('usuario_rol').value = rol;
            document.getElementById('usuario_sede').value = sede;
        }
    </script>
</body>
</html>
