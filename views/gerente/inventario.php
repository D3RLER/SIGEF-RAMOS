<?php
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
// Proteger la vista solo para Gerente
AuthMiddleware::checkRole(['Gerente', 'Admin']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - SIGEF-RAMOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Roboto', sans-serif; }
        .wrapper { display: flex; width: 100%; align-items: stretch; }
        
        /* Sidebar styling copiado de dashboard_gerente o usar include */
        #sidebar { min-width: 250px; max-width: 250px; min-height: 100vh; background-color: #212529; color: #fff; }
        #sidebar .sidebar-header { padding: 20px; background: #1a1e21; border-bottom: 2px solid #6D0032; }
        #sidebar ul.components { padding: 20px 0; }
        #sidebar ul li a { padding: 12px 20px; font-size: 1.1em; display: block; color: #adb5bd; text-decoration: none; }
        #sidebar ul li a:hover, #sidebar ul li.active > a { color: #fff; background: #6D0032; border-left: 4px solid #fff; }
        #sidebar ul li a i { margin-right: 10px; }
        
        #content { width: 100%; transition: all 0.3s; }
        .header-custom { background-color: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 15px 20px; }
        .bg-guinda { background-color: #6D0032 !important; color: white;}
        .text-guinda { color: #6D0032 !important; }
        .btn-guinda { background-color: #6D0032; color: white; }
        .btn-guinda:hover { background-color: #520025; color: white; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar Menu -->
        <nav id="sidebar">
            <div class="sidebar-header text-center">
                <h4 class="mb-0 fw-bold">SIGEF-RAMOS</h4>
                <small class="text-white-50">Panel Gerencial</small>
            </div>
            <ul class="list-unstyled components">
                <li><a href="index.php?controller=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="index.php?controller=usuario"><i class="bi bi-people-fill"></i> Usuarios</a></li>
                <li class="active"><a href="index.php?controller=inventario"><i class="bi bi-box-seam"></i> Inventario</a></li>
                <li><a href="index.php?controller=sede"><i class="bi bi-building"></i> Sedes</a></li>
                <li class="mt-5 border-top border-secondary pt-3">
                    <a href="index.php?controller=auth&action=logout" class="text-danger"><i class="bi bi-box-arrow-left"></i> Cerrar Sesion</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg header-custom mb-4">
                <div class="container-fluid">
                    <span class="navbar-brand fw-bold text-dark"><i class="bi bi-box-seam text-guinda me-2"></i> Gestion de Inventario (CU-02)</span>
                    <span class="text-muted"><i class="bi bi-person-circle me-1"></i> Hola, <strong><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Gerente'); ?></strong></span>
                </div>
            </nav>

            <div class="container-fluid px-4 pb-5">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-dark fw-bold">Catalogo de Productos y Servicios Base</h6>
                        <!-- Boton para abrir modal de nuevo producto -->
                        <button class="btn btn-sm btn-guinda" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                            <i class="bi bi-plus-lg me-1"></i>Nuevo Producto
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted small">
                                    <tr>
                                        <th class="ps-4">Producto/Servicio</th>
                                        <th>Categoria</th>
                                        <th>Sede</th>
                                        <th class="text-center">Stock</th>
                                        <th class="text-end">P. Compra</th>
                                        <th class="text-end pe-4">P. Venta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($productos)): ?>
                                        <tr><td colspan="6" class="text-center text-muted py-4">No hay productos registrados en el inventario.</td></tr>
                                    <?php
else: ?>
                                        <?php foreach ($productos as $item): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark"><?php echo htmlspecialchars($item['producto']); ?></td>
                                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['categoria']); ?></span></td>
                                            <td><?php echo htmlspecialchars($item['sede_nombre']); ?></td>
                                            <td class="text-center">
                                                <!-- ALERTA VISUAL: Badge rojo si stock es menor al minimo o menor a 3 unids -->
                                                <?php if ($item['stock'] < 3 || $item['stock'] <= $item['stock_minimo']): ?>
                                                    <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm" title="Stock Critico">
                                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo $item['stock']; ?>
                                                    </span>
                                                <?php
        else: ?>
                                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                                        <?php echo $item['stock']; ?>
                                                    </span>
                                                <?php
        endif; ?>
                                            </td>
                                            <td class="text-end text-muted">S/ <?php echo number_format($item['precio_compra'], 2); ?></td>
                                            <td class="text-end fw-bold text-success pe-4">S/ <?php echo number_format($item['precio_venta'], 2); ?></td>
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
        </div>
    </div>

    <!-- Modal Agregar Producto -->
    <div class="modal fade" id="modalAgregar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="index.php?controller=inventario&action=agregar" method="POST">
                    <div class="modal-header bg-guinda text-white">
                        <h5 class="modal-title"><i class="bi bi-box-seam me-2"></i>Registrar Nuevo Inventario</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre / Modelo</label>
                            <input type="text" name="producto" class="form-control" required placeholder="Ej: Ataud Cedro Presidencial">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Categoria</label>
                                <select name="categoria" class="form-select" required>
                                    <option value="Ataudes">Ataudes</option>
                                    <option value="Traslados">Traslados</option>
                                    <option value="Salas de Velacion">Salas de Velacion</option>
                                    <option value="Arreglos Florales">Arreglos Florales</option>
                                    <option value="Recordatorios Funebres">Recordatorios Funebres</option>
                                    <option value="Gestion de Tramites">Gestion de Tramites</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sede</label>
                                <select name="sede_id" class="form-select" required>
                                    <option value="1">Ilo</option>
                                    <option value="2">Moquegua</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Stock Actual</label>
                                <input type="number" name="stock" class="form-control" required min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Stock Minimo (Alerta)</label>
                                <input type="number" name="stock_minimo" class="form-control" required min="1" value="3">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Precio Compra Base (S/)</label>
                                <input type="number" step="0.01" name="precio_compra" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-success fw-bold">Precio Venta (S/)</label>
                                <input type="number" step="0.01" name="precio_venta" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-guinda">Guardar en Inventario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
