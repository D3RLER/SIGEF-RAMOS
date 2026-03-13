<?php
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
// Permitir Vendedores y Gerentes
AuthMiddleware::checkRole(['Vendedor', 'Gerente']);
$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Difunto y Deudo - SIGEF-RAMOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Roboto', sans-serif; }
        .header-custom { background-color: #6D0032; color: #fff; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .card-header-guinda { background-color: #6D0032; color: white; border-radius: 12px 12px 0 0 !important; font-weight: 500; }
        .btn-guinda { background-color: #6D0032; color: white; border: none; font-weight: 500; transition: all 0.3s; }
        .btn-guinda:hover { background-color: #520025; color: white; transform: translateY(-1px); }
        .section-title { color: #6D0032; font-weight: bold; border-bottom: 2px solid #6D0032; padding-bottom: 5px; margin-bottom: 20px; }
        .input-group-text.bg-guinda { background-color: #6D0032; color: white; border: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark header-custom mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php?controller=dashboard">SIGEF-RAMOS | <?php echo $rol; ?></a>
            <div class="d-flex">
                <a href="index.php?controller=auth&action=logout" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <h2 class="mb-4 text-secondary"><i class="bi bi-person-plus-fill me-2"></i>Modulo de Registro (CU-06)</h2>
        
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['success_msg'];
    unset($_SESSION['success_msg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
endif; ?>
        
        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['error_msg'];
    unset($_SESSION['error_msg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
endif; ?>

        <form action="../../controllers/DifuntoController.php" method="POST" id="formRegistro">
            <input type="hidden" name="action" value="registrar_difunto">
            <!-- Hidden para saber si usamos deudo existente -->
            <input type="hidden" name="deudo_id_existente" id="deudo_id_existente" value="">

            <div class="row gx-4">
                <!-- COLUMNA DEUDO -->
                <div class="col-lg-6 mb-4">
                    <div class="card card-custom h-100">
                        <div class="card-header card-header-guinda py-3">
                            <i class="bi bi-people-fill me-2"></i> 1. Datos del Deudo (Familiar)
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold">DNI del Deudo *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-guinda"><i class="bi bi-credit-card-2-front"></i></span>
                                    <input type="text" class="form-control" id="deudo_dni" name="deudo_dni" maxlength="20" required placeholder="Ingrese y presione Enter o Buscar">
                                    <button class="btn btn-outline-secondary" type="button" id="btnBuscarDni">Buscar</button>
                                </div>
                                <div id="dniMsj" class="form-text mt-2"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nombres *</label>
                                    <input type="text" class="form-control deudo-field" id="deudo_nombres" name="deudo_nombres" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Apellidos *</label>
                                    <input type="text" class="form-control deudo-field" id="deudo_apellidos" name="deudo_apellidos" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telefono</label>
                                    <input type="text" class="form-control deudo-field" id="deudo_telefono" name="deudo_telefono">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control deudo-field" id="deudo_email" name="deudo_email">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Direccion</label>
                                <input type="text" class="form-control deudo-field" id="deudo_direccion" name="deudo_direccion">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DIFUNTO -->
                <div class="col-lg-6 mb-4">
                    <div class="card card-custom h-100">
                        <div class="card-header card-header-guinda py-3">
                            <i class="bi bi-person-x-fill me-2"></i> 2. Datos del Difunto
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label">DNI del Difunto</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-credit-card-2-front text-muted"></i></span>
                                    <input type="text" class="form-control" name="difunto_dni" maxlength="20">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nombres *</label>
                                    <input type="text" class="form-control" name="difunto_nombres" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Apellidos *</label>
                                    <input type="text" class="form-control" name="difunto_apellidos" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" name="fecha_nacimiento">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha de Fallecimiento *</label>
                                    <input type="date" class="form-control" name="fecha_fallecimiento" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Causa de Muerte</label>
                                <input type="text" class="form-control" name="causa_muerte" placeholder="Causa clinica">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTONES -->
            <div class="text-end mt-2">
                <a href="index.php?controller=dashboard" class="btn btn-light border px-4 me-2">Cancelar</a>
                <button type="submit" class="btn btn-guinda px-5"><i class="bi bi-save me-2"></i> Registrar Maestro</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btnBuscar = document.getElementById("btnBuscarDni");
            const inputDni = document.getElementById("deudo_dni");
            const deudoIdExistente = document.getElementById("deudo_id_existente");
            const msj = document.getElementById("dniMsj");
            const deudoFields = document.querySelectorAll(".deudo-field");

            function buscarDni() {
                const dni = inputDni.value.trim();
                if (dni.length < 5) return;

                msj.innerHTML = '<span class="text-info"><i class="spinner-border spinner-border-sm"></i> Buscando...</span>';

                fetch(`index.php?controller=difunto&action=buscar_deudo&dni=${dni}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Encontrado: Autocompletar y bloquear campos
                            msj.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> Deudo encontrado. Campos bloqueados.</span>';
                            deudoIdExistente.value = data.data.id;
                            document.getElementById("deudo_nombres").value = data.data.nombres;
                            document.getElementById("deudo_apellidos").value = data.data.apellidos;
                            document.getElementById("deudo_telefono").value = data.data.telefono;
                            document.getElementById("deudo_direccion").value = data.data.direccion;
                            document.getElementById("deudo_email").value = data.data.email;
                            
                            deudoFields.forEach(field => field.setAttribute('readonly', true));
                        } else {
                            // No encontrado: Limpiar para nuevo registro
                            msj.innerHTML = '<span class="text-primary"><i class="bi bi-info-circle"></i> DNI no registrado. Proceda a llenarlo.</span>';
                            deudoIdExistente.value = "";
                            deudoFields.forEach(field => {
                                field.value = "";
                                field.removeAttribute('readonly');
                            });
                        }
                    })
                    .catch(error => {
                        msj.innerHTML = '<span class="text-danger">Error de conexion.</span>';
                    });
            }

            btnBuscar.addEventListener("click", buscarDni);
            inputDni.addEventListener("keypress", function(e) {
                if(e.key === "Enter") {
                    e.preventDefault();
                    buscarDni();
                }
            });
            // Buscar al perder foco
            inputDni.addEventListener("blur", buscarDni);
        });
    </script>
</body>
</html>
