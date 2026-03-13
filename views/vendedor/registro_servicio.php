<?php
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
AuthMiddleware::checkRole(['Vendedor', 'Gerente']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Integral de Servicios - SIGEF-RAMOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Alertas elegantes -->
    <style>
        body { background-color: #f4f6f9; font-family: 'Roboto', sans-serif; }
        .bg-guinda { background-color: #6D0032 !important; color: white; }
        .text-guinda { color: #6D0032 !important; }
        .btn-guinda { background-color: #6D0032; color: white; border: none; }
        .btn-guinda:hover { background-color: #520025; color: white; }
        .nav-pills .nav-link.active, .nav-pills .show>.nav-link { background-color: #6D0032; }
        .nav-link { color: #6c757d; font-weight: 500;}
        .wizard-step-icon { width: 35px; height: 35px; border-radius: 50%; display: inline-flex; justify-content: center; align-items: center; background-color: #e9ecef; color: #6c757d; margin-right: 10px; font-weight: bold;}
        .nav-link.active .wizard-step-icon { background-color: rgba(255,255,255,0.2); color: white; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="index.php?controller=dashboard"><i class="bi bi-arrow-left me-2"></i>Volver al Panel</a>
            <span class="text-muted"><i class="bi bi-person-badge text-guinda me-1"></i> Asesor: <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong></span>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-guinda py-4 text-center">
                        <h4 class="mb-0 fw-bold"><i class="bi bi-file-earmark-medical me-2"></i>Registro Integral de Servicio Diferido (CU-06)</h4>
                        <p class="mb-0 text-white-50">Vinculacion de Deudo, Fallecido y Seleccion de Servicio</p>
                    </div>
                    
                    <div class="card-body p-0">
                        <!-- Stepper Headers -->
                        <ul class="nav nav-pills nav-fill bg-light border-bottom p-3" id="wizardPills" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill d-flex align-items-center justify-content-center" id="step1-tab" data-bs-toggle="pill" data-bs-target="#step1" type="button" role="tab" aria-selected="true">
                                    <span class="wizard-step-icon">1</span> Datos del Familiar (Deudo)
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill d-flex align-items-center justify-content-center" id="step2-tab" data-bs-toggle="pill" data-bs-target="#step2" type="button" role="tab" aria-selected="false" disabled>
                                    <span class="wizard-step-icon">2</span> Datos del Fallecido
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill d-flex align-items-center justify-content-center" id="step3-tab" data-bs-toggle="pill" data-bs-target="#step3" type="button" role="tab" aria-selected="false" disabled>
                                    <span class="wizard-step-icon">3</span> Seleccion de Servicio
                                </button>
                            </li>
                        </ul>

                        <!-- Formularios (Pestanas) -->
                        <div class="p-4 p-md-5">
                            <form id="registroIntegralForm" action="index.php?controller=difunto&action=guardarIntegral" method="POST">
                                <div class="tab-content" id="wizardContent">
                                    
                                    <!-- PASO 1: DEUDO -->
                                    <div class="tab-pane fade show active" id="step1" role="tabpanel">
                                        <h5 class="text-guinda border-bottom pb-2 mb-4"><i class="bi bi-person-vcard me-2"></i>1. Identificacion del Deudo</h5>
                                        
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">DNI del Deudo <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i class="bi bi-credit-card-2-front text-muted"></i></span>
                                                    <input type="text" class="form-control" id="dni_deudo" name="dni_deudo" maxlength="8" pattern="\d{8}" required placeholder="Buscar DNI...">
                                                    <button class="btn btn-outline-secondary" type="button" id="btnBuscarDeudo" title="Buscar en RENIEC (Simulado)"><i class="bi bi-search"></i></button>
                                                </div>
                                                <div class="invalid-feedback" id="dniFeedback">El DNI debe tener exactamente 8 digitos numericos.</div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nombres Completos</label>
                                                <input type="text" class="form-control" id="nombres_deudo" name="nombres_deudo" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Apellidos Paterno y Materno</label>
                                                <input type="text" class="form-control" id="apellidos_deudo" name="apellidos_deudo" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Telefono / Celular de Contacto <span class="text-danger">*</span></label>
                                                <input type="tel" class="form-control" id="telefono_deudo" name="telefono_deudo" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Direccion (Donde se llevara la capilla, si aplica)</label>
                                                <input type="text" class="form-control" id="direccion_deudo" name="direccion_deudo">
                                            </div>
                                        </div>

                                        <div class="mt-5 text-end">
                                            <button type="button" class="btn btn-guinda px-4 py-2" onclick="nextTab('step2-tab')">Siguiente Paso: Fallecido <i class="bi bi-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>

                                    <!-- PASO 2: FALLECIDO -->
                                    <div class="tab-pane fade" id="step2" role="tabpanel">
                                        <h5 class="text-guinda border-bottom pb-2 mb-4"><i class="bi bi-person-x me-2"></i>2. Datos del Fallecido</h5>
                                        
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">DNI del Fallecido</label>
                                                <input type="text" class="form-control" name="dni_difunto" maxlength="8" pattern="\d{8}">
                                                <small class="text-muted">Si no cuenta con DNI, dejar en blanco.</small>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">Nombres Completos <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="nombres_difunto" name="nombres_difunto" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">Apellidos Completos <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="apellidos_difunto" name="apellidos_difunto" required>
                                            </div>
                                            
                                            <div class="col-md-4 mt-4">
                                                <label class="form-label fw-bold">Fecha de Fallecimiento <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="fecha_fallecimiento" name="fecha_fallecimiento" required max="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div class="col-md-8 mt-4">
                                                <label class="form-label">Causa Principal de Muerte (Certificado de Defuncion)</label>
                                                <input type="text" class="form-control" name="causa_muerte" placeholder="Ej: Paro Cardiorrespiratorio">
                                            </div>
                                        </div>

                                        <div class="mt-5 d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-secondary px-4 py-2" onclick="prevTab('step1-tab')"><i class="bi bi-arrow-left me-2"></i> Volver a Deudo</button>
                                            <button type="button" class="btn btn-guinda px-4 py-2" onclick="nextTab('step3-tab')">Siguiente: Servicio <i class="bi bi-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>

                                    <!-- PASO 3: SERVICIO -->
                                    <div class="tab-pane fade" id="step3" role="tabpanel">
                                        <h5 class="text-guinda border-bottom pb-2 mb-4"><i class="bi bi-cart-check me-2"></i>3. Configuracion del Servicio a Brindar</h5>

                                        <div class="row g-3">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Tipo de Servicio Base <span class="text-danger">*</span></label>
                                                <select name="tipo_servicio" class="form-select" required>
                                                    <option value="">Seleccione paquete institucional...</option>
                                                    <option value="Servicio Economico">Servicio Economico (Madera Basica)</option>
                                                    <option value="Servicio Clasico">Servicio Clasico (Madera Fina + Capilla)</option>
                                                    <option value="Servicio Premium">Servicio Premium (Ataud Presidencial + Carroza)</option>
                                                    <option value="Cremacion">Cremacion Directa (Sin Ataud, incluye Urna)</option>
                                                    <option value="Traslado a Provincia">Traslado a Provincia</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Sede Prestataria <span class="text-danger">*</span></label>
                                                <select name="sede_id" class="form-select text-primary" required>
                                                    <option value="<?php echo $_SESSION['sede_id'] ?? 1; ?>">Sede Actual (Pre-seleccionada)</option>
                                                    <option value="1">Ilo</option>
                                                    <option value="2">Moquegua</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8 mb-3">
                                                <label class="form-label">Detalles / Notas del Contrato (Opcional)</label>
                                                <textarea name="descripcion" class="form-control" rows="3" placeholder="Ej: Se requiere salon velatorio a partir de las 8PM..."></textarea>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-success fs-5">Precio Total (S/) <span class="text-danger">*</span></label>
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text bg-success text-white">S/</span>
                                                    <input type="number" step="0.01" class="form-control fw-bold text-success text-end" name="precio" required placeholder="0.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-5 d-flex justify-content-between border-top pt-4">
                                            <button type="button" class="btn btn-outline-secondary px-4 py-2" onclick="prevTab('step2-tab')"><i class="bi bi-arrow-left me-2"></i> Volver a Fallecido</button>
                                            <button type="submit" class="btn btn-guinda btn-lg px-5 shadow"><i class="bi bi-check2-circle me-2"></i>Finalizar y Guardar Contrato</button>
                                        </div>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Funciones para Navegacion del Wizard Stepper
        function validateStep(stepId) {
            const currentTabPane = document.getElementById(stepId);
            const inputs = currentTabPane.querySelectorAll('[required]');
            let isValid = true;
            
            // Logica Especial: Validar DNI Deudo en JS nativo (8 digitos)
            if (stepId === 'step1') {
                const dniInput = document.getElementById('dni_deudo');
                const dniRegex = /^\d{8}$/;
                if (!dniRegex.test(dniInput.value)) {
                    dniInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    dniInput.classList.remove('is-invalid');
                    dniInput.classList.add('is-valid');
                }
            }

            // Validar HTML5 base
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    isValid = false;
                }
            });
            return isValid;
        }

        function nextTab(targetTabId) {
            // Check active tab validate
            const actTab = document.querySelector('.tab-pane.show.active').id;
            if(!validateStep(actTab)) return;

            // Enable next tab
            const targetTab = new bootstrap.Tab(document.getElementById(targetTabId));
            document.getElementById(targetTabId).disabled = false;
            targetTab.show();
        }

        function prevTab(targetTabId) {
            const targetTab = new bootstrap.Tab(document.getElementById(targetTabId));
            targetTab.show();
        }
        
        // Simulacion: Comportamiento del boton AJAX
        document.getElementById('btnBuscarDeudo').addEventListener('click', function() {
            const dni = document.getElementById('dni_deudo').value;
            if(dni.length !== 8) {
                Swal.fire({ icon: 'warning', title: 'DNI Invalido', text: 'Debe contener exactly 8 digitos numericos.', confirmButtonColor: '#6D0032'});
                return;
            }
            
            // Aqui iria el fetch() real a tu AJAX endpoint (ej: ../../controllers/DifuntoController.php?action=buscar_deudo)
            // Simulando una carga exitosa:
            Swal.fire({
                title: 'Buscando...',
                text: 'Consultando base de datos...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            setTimeout(() => {
                Swal.close();
                // Simula que trajo los datos
                Swal.fire({ icon: 'success', title: 'Deudo Nuevo', text: 'No se encontro en BD. Proceda a registrar sus datos.', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                document.getElementById('nombres_deudo').focus();
            }, 800);
        });
    </script>
</body>
</html>
