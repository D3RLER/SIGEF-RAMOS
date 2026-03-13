<?php // No session_start() here as it relies on router ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizador Inteligente - SIGEF-RAMOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Roboto', sans-serif; }
        .bg-guinda { background-color: #6D0032 !important; color: white;}
        .text-guinda { color: #6D0032 !important; }
        .navbar-custom { background-color: #6D0032; }
        .price-display { font-size: 2.5rem; font-weight: 700; color: #6D0032; }
        
        /* Estilos para el seleccionador visual */
        .option-card {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            overflow: hidden;
            background: #fff;
            height: 100%;
        }
        .option-card:hover { border-color: #6D0032; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(109,0,50,0.1); }
        .option-card.selected { border-color: #6D0032; background-color: rgba(109, 0, 50, 0.05); }
        .option-card.selected::after {
            content: '\F633'; /* check-circle-fill de Bootstrap Icons */
            font-family: 'bootstrap-icons';
            position: absolute;
            top: 10px;
            right: 15px;
            color: #6D0032;
            font-size: 1.5rem;
        }
        .option-img-placeholder {
            width: 100%;
            height: 140px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            border-bottom: 1px solid #e9ecef;
            object-fit: cover;
        }
        /* Ocultar los radio/checkbox reales */
        .visually-hidden-input { position: absolute; opacity: 0; pointer-events: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-arrow-left me-2"></i> Volver al Inicio</a>
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Cliente'): ?>
                <span class="text-white"><i class="bi bi-person-check-fill me-1"></i> Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?>. Tu proforma se guardara automaticamente en tu perfil.</span>
            <?php
else: ?>
                <span class="text-white-50"><i class="bi bi-info-circle me-1"></i> Modo Invitado. Descargaras un PDF unico.</span>
            <?php
endif; ?>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                    <div class="card-header bg-guinda py-3">
                        <h4 class="mb-0 fw-bold"><i class="bi bi-calculator me-2"></i>Generador de Proformas</h4>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="index.php?controller=public&action=generarProformaPDF" method="POST" target="_blank">
                            <p class="text-muted mb-4">Cotiza nuestros servicios al instante de manera transparente. Descarga un presupuesto oficial al instante.</p>
                            
                            <!-- Sede requerida para el registro -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Sede de Cotizacion</label>
                                <select class="form-select" name="sede_id" required>
                                    <option value="1">Ilo</option>
                                    <option value="2">Moquegua</option>
                                </select>
                            </div>

                            <!-- INICIO ITERACION DINAMICA DE CATEGORIAS -->
                            <?php
$step = 1;
$icons = [
    'Ataudes' => 'bi-box-seam',
    'Traslados' => 'bi-car-front-fill',
    'Salas de Velacion' => 'bi-house-heart-fill',
    'Arreglos Florales' => 'bi-flower1',
    'Recordatorios Funebres' => 'bi-card-text',
    'Gestion de Tramites' => 'bi-file-earmark-text-fill'
];

foreach ($productos as $categoria => $lista):
    $iconClass = $icons[$categoria] ?? 'bi-box-seam';
?>
                                <div class="mb-5">
                                    <label class="form-label fw-bold text-dark fs-5 mb-3 border-bottom pb-2 w-100"><?php echo $step . '. ' . htmlspecialchars($categoria); ?></label>
                                    <div class="row g-3">
                                        <?php foreach ($lista as $index => $prod): ?>
                                            <div class="col-md-4 position-relative">
                                                
                                                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Admin'): ?>
                                                <!-- Controles CRUD para Administradores -->
                                                <div class="position-absolute ms-3 mt-2 d-flex gap-1" style="z-index: 10; top: 0; left: 0;">
                                                    <button type="button" class="btn btn-sm btn-warning shadow-sm border text-dark py-0 px-2" onclick="abrirEditModal(event, <?php echo $prod['id']; ?>, '<?php echo htmlspecialchars(addslashes($prod['producto']), ENT_QUOTES); ?>', '<?php echo htmlspecialchars(addslashes($categoria), ENT_QUOTES); ?>', <?php echo $prod['precio_venta']; ?>)" title="Editar">
                                                        <i class="bi bi-pencil-fill" style="font-size: 0.8rem;"></i>
                                                    </button>
                                                    <a href="index.php?controller=inventario&action=eliminar&id=<?php echo $prod['id']; ?>&source=cotizador" class="btn btn-sm btn-danger shadow-sm py-0 px-2" onclick="return confirm('¿Confirma eliminar definitivamente este elemento del catalogo?');" title="Eliminar">
                                                        <i class="bi bi-trash-fill" style="font-size: 0.8rem;"></i>
                                                    </a>
                                                </div>
                                                <?php
        endif; ?>

                                                <label class="option-card w-100 position-relative text-center" id="card-prod-<?php echo $prod['id']; ?>">
                                                    <!-- Se usa el array items[] para enviar todos los IDs seleccionados -->
                                                    <input type="checkbox" name="items[]" value="<?php echo $prod['id']; ?>" class="visually-hidden-input precio-trigger product-input <?php echo($categoria === 'Ataudes') ? 'exclusive-check' : ''; ?>" data-precio="<?php echo $prod['precio_venta']; ?>">
                                                    <div class="option-img-placeholder bg-light">
                                                        <?php if (!empty($prod['imagen'])): ?>
                                                            <img src="<?php echo htmlspecialchars($prod['imagen']); ?>" alt="<?php echo htmlspecialchars($prod['producto']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                        <?php
        else: ?>
                                                            <i class="bi <?php echo $iconClass; ?> fs-1"></i>
                                                        <?php
        endif; ?>
                                                    </div>
                                                    <div class="p-3">
                                                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($prod['producto']); ?></h6>
                                                        <span class="text-guinda fw-bold">S/. <?php echo number_format($prod['precio_venta'], 2); ?></span>
                                                    </div>
                                                </label>
                                            </div>
                                        <?php
    endforeach; ?>
                                        
                                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Admin'): ?>
                                            <!-- Boton Agregar Producto Explicitly in Grid -->
                                            <div class="col-md-4">
                                                <label class="option-card w-100 position-relative text-center d-flex align-items-center justify-content-center" style="border: 2px dashed #6c757d; background: transparent; cursor: pointer; min-height: 220px;" onclick="abrirAddModal(event, '<?php echo htmlspecialchars($categoria); ?>')">
                                                    <div class="p-4 text-secondary hover-guinda-text">
                                                        <i class="bi bi-plus-circle fs-1 mb-2"></i>
                                                        <h6 class="fw-bold">Agregar <?php echo htmlspecialchars($categoria); ?></h6>
                                                    </div>
                                                </label>
                                            </div>
                                        <?php
    endif; ?>
                                        
                                    </div>
                                </div>
                            <?php
    $step++;
endforeach;
?>
                            <!-- FIN ITERACION DINAMICA -->

                            <div class="text-center bg-light p-4 rounded mb-4 border">
                                <span class="text-muted text-uppercase fw-bold">Total Estimado</span>
                                <div class="price-display">S/. <span id="totalEstimado">1500.00</span></div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-lg bg-guinda fw-bold py-3"><i class="bi bi-download me-2"></i> Descargar Proforma PDF</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Admin'): ?>
    <!-- Modal Quick Add Catalog -->
    <div class="modal fade" id="modalQuickAdd" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="index.php?controller=inventario&action=agregar" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="source" value="cotizador">
                    <div class="modal-header bg-guinda text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-box-seam me-2"></i>Agregar al Catalogo Web</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre del Producto / Servicio</label>
                            <input type="text" name="producto" class="form-control" required placeholder="Ej: Urna de Marmol Premium">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Categoria</label>
                            <select name="categoria" id="add-categoria" class="form-select" required>
                                <option value="Ataudes">Ataudes</option>
                                <option value="Traslados">Traslados</option>
                                <option value="Salas de Velacion">Salas de Velacion</option>
                                <option value="Arreglos Florales">Arreglos Florales</option>
                                <option value="Recordatorios Funebres">Recordatorios Funebres</option>
                                <option value="Gestion de Tramites">Gestion de Tramites</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Precio (S/)</label>
                            <input type="number" step="0.01" name="precio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Imagen (Opcional)</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-guinda"><i class="bi bi-save me-1"></i> Publicar en Cotizador</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
endif; ?>

    <!-- Modal Editar Catalog -->
    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Admin'): ?>
    <div class="modal fade" id="modalQuickEdit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="index.php?controller=inventario&action=actualizar" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="source" value="cotizador">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Producto Web</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre del Producto / Servicio</label>
                            <input type="text" name="producto" id="edit-producto" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Categoria</label>
                            <select name="categoria" id="edit-categoria" class="form-select" required>
                                <option value="Ataudes">Ataudes</option>
                                <option value="Traslados">Traslados</option>
                                <option value="Salas de Velacion">Salas de Velacion</option>
                                <option value="Arreglos Florales">Arreglos Florales</option>
                                <option value="Recordatorios Funebres">Recordatorios Funebres</option>
                                <option value="Gestion de Tramites">Gestion de Tramites</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Precio (S/)</label>
                            <input type="number" step="0.01" name="precio" id="edit-precio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cambiar Imagen (Dejar vacio para mantener)</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning fw-bold"><i class="bi bi-save me-1"></i> Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function abrirAddModal(e, categoria) {
            e.stopPropagation();
            e.preventDefault();
            document.getElementById('add-categoria').value = categoria;
            var addModal = new bootstrap.Modal(document.getElementById('modalQuickAdd'));
            addModal.show();
        }

        function abrirEditModal(e, id, producto, categoria, precio) {
            e.stopPropagation(); // Evitar click en el label
            e.preventDefault();
            
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-producto').value = producto;
            document.getElementById('edit-categoria').value = categoria;
            document.getElementById('edit-precio').value = precio;
            
            var editModal = new bootstrap.Modal(document.getElementById('modalQuickEdit'));
            editModal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const totalDisplay = document.getElementById('totalEstimado');
            const productInputs = document.querySelectorAll('.product-input');

            // Exclusive logic for 'Ataudes'
            const exclusiveChecks = document.querySelectorAll('.exclusive-check');
            exclusiveChecks.forEach(chk => {
                chk.addEventListener('click', function() {
                    if (this.checked) {
                        exclusiveChecks.forEach(other => {
                            if (other !== this) {
                                other.checked = false;
                            }
                        });
                    }
                });
            });

            function updateUI() {
                let total = 0;
                
                productInputs.forEach(input => {
                    const card = input.closest('.option-card');
                    if (input.checked) {
                        card.classList.add('selected');
                        total += parseFloat(input.dataset.precio);
                    } else {
                        card.classList.remove('selected');
                    }
                });

                totalDisplay.textContent = total.toFixed(2);
            }

            // Escuchar cambios
            productInputs.forEach(el => el.addEventListener('change', updateUI));
            
            // Render inicial
            updateUI();
        });
    </script>
</body>
</html>
