<?php // No session_start() here as it relies on router ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funeraria SIGEF-RAMOS - Acompanando con Dignidad</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; overflow-x: hidden; }
        .bg-guinda { background-color: #6D0032 !important; }
        .text-guinda { color: #6D0032 !important; }
        .btn-guinda { background-color: #6D0032; color: white; border: none; transition: 0.3s; }
        .btn-guinda:hover { background-color: #520025; color: white; transform: translateY(-2px); }
        .hero-section { background: linear-gradient(rgba(109, 0, 50, 0.85), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1469037248107-b0895355eb6f?q=80&w=2000&auto=format&fit=crop') center/cover; padding: 120px 0; color: white; }
        .service-card { border: none; border-radius: 12px; transition: 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .service-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(109,0,50,0.1); }
        .icon-circle { width: 80px; height: 80px; background-color: rgba(109, 0, 50, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; }
    </style>
</head>
<body>

    <!-- Navegacion Publica -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-guinda py-3 shadow">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="index.php">SIGEF-RAMOS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPublic"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navPublic">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#servicios">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                    
                    <?php if (isset($_SESSION['id'])): ?>
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                            <?php
    $ctrl = 'cliente';
    $s_rol = strtolower($_SESSION['rol']);
    if ($s_rol === 'admin')
        $ctrl = 'hub';
    elseif ($s_rol === 'gerente' || $s_rol === 'vendedor')
        $ctrl = 'dashboard';
    elseif ($s_rol === 'operario')
        $ctrl = 'operario';
?>
                            <a href="index.php?controller=<?php echo $ctrl; ?>" class="btn btn-outline-light rounded-pill px-4">
                                <i class="bi bi-person-circle me-1"></i> Mi Panel (<?php echo $_SESSION['nombre']; ?>)
                            </a>
                        </li>
                    <?php
else: ?>
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                            <button type="button" class="btn btn-outline-light rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#clientLoginModal">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Acceso Clientes
                            </button>
                        </li>
                    <?php
endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Header -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Acompanando con Respeto y Dignidad</h1>
            <p class="lead mb-5 col-md-8 mx-auto opacity-75">Brindamos asistencia exequial integral en Ilo y Moquegua, aliviando la carga en los momentos mas dificiles para que honres a tus seres queridos.</p>
            <a href="index.php?controller=public&action=cotizador" class="btn btn-light btn-lg text-guinda fw-bold rounded-pill px-5 shadow">
                <i class="bi bi-calculator me-2"></i> Cotizar Servicio Online
            </a>
        </div>
    </section>

    <!-- Catalogo de Servicios -->
    <section id="servicios" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark">Nuestros Servicios</h2>
                <div class="mx-auto bg-guinda mt-3" style="height: 4px; width: 60px; border-radius: 2px;"></div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card service-card h-100 text-center p-4">
                        <div class="icon-circle mb-4">
                            <i class="bi bi-moon-stars text-guinda fs-1"></i>
                        </div>
                        <h4 class="fw-bold">Servicio Estandar</h4>
                        <p class="text-muted">Ataud de madera prensada, capilla basica, carroza funebre local y tramites por defuncion.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card h-100 text-center p-4">
                        <div class="icon-circle mb-4">
                            <i class="bi bi-gem text-guinda fs-1"></i>
                        </div>
                        <h4 class="fw-bold">Servicio Premium</h4>
                        <p class="text-muted">Ataud de madera cedro/caoba tallada, capilla ardiente de lujo, carroza premium y obituario.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card h-100 text-center p-4">
                        <div class="icon-circle mb-4">
                            <i class="bi bi-fire text-guinda fs-1"></i>
                        </div>
                        <h4 class="fw-bold">Cremacion Directa</h4>
                        <p class="text-muted">Traslado a crematorio, urna cineraria estandar, certificado de cremacion y tramites de registro civil.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer / Contacto -->
    <section id="contacto" class="bg-dark text-white py-5">
        <div class="container pt-4">
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 mb-4">
                    <h5 class="fw-bold text-uppercase mb-4">Sobre SIGEF-RAMOS</h5>
                    <p class="text-white-50 small">Mas de 20 anos acompanando a las familias del sur del Peru. Compromiso, empatia y el trato humano que mereces.</p>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <h5 class="fw-bold text-uppercase mb-4">Nuestras Sedes</h5>
                    <ul class="list-unstyled text-white-50 small">
                        <li class="mb-3"><i class="bi bi-geo-alt-fill text-guinda me-2"></i> <strong>Sede Ilo:</strong> Calle Principal 123</li>
                        <li class="mb-3"><i class="bi bi-geo-alt-fill text-guinda me-2"></i> <strong>Sede Moquegua:</strong> Av. Central 456</li>
                        <li><i class="bi bi-telephone-fill text-guinda me-2"></i> Atencion 24/7: (053) 48-####</li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold text-uppercase mb-4">Atencion Exclusiva</h5>
                    <p class="text-white-50 small">Nuestro equipo esta comprometido en brindarle el mejor servicio. Los accesos al sistema son gestionados directamente en nuestras oficinas.</p>
                </div>
            </div>
            <hr class="mt-4 mb-4 border-secondary">
            <div class="text-center text-white-50 small">
                © <?php echo date('Y'); ?> Funeraria Ramos S.A.C. Todos los derechos reservados.
            </div>
        </div>
    </section>

    <!-- Modal Híbrido de Cliente (Login/Registro) -->
    <div class="modal fade" id="clientLoginModal" tabindex="-1" aria-labelledby="clientLoginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; overflow: hidden; border: none;">
                <div class="modal-header bg-guinda text-white" style="border-bottom: 4px solid #520025; padding: 20px 25px;">
                    <h5 class="modal-title fw-bold" id="clientLoginModalLabel"><i class="bi bi-person-circle me-2"></i> Portal del Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs nav-justified" id="clientAuthTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo !isset($_SESSION['show_register_modal']) ? 'active fw-bold' : 'text-muted'; ?>" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-panel" type="button" role="tab" style="color: #6D0032;">Iniciar Sesión</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo isset($_SESSION['show_register_modal']) ? 'active fw-bold' : 'text-muted'; ?>" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-panel" type="button" role="tab" style="color: #6D0032;">Crear Cuenta</button>
                        </li>
                    </ul>

                    <!-- Tab Contents -->
                    <div class="tab-content" id="clientAuthTabsContent">
                        
                        <!-- Panel de Login -->
                        <div class="tab-pane fade <?php echo !isset($_SESSION['show_register_modal']) ? 'show active' : ''; ?> p-4" id="login-panel" role="tabpanel">
                            <?php if (isset($_SESSION['error_login'])): ?>
                                <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $_SESSION['error_login'];
    unset($_SESSION['error_login']); ?></div>
                            <?php
endif; ?>
                            
                            <form action="index.php?controller=auth&action=procesar_login_cliente" method="POST">
                                <div class="mb-4">
                                    <label class="form-label text-secondary fw-semibold">Correo o DNI</label>
                                    <input type="text" class="form-control form-control-lg" name="identificador" required placeholder="Ingresa tu DNI o Correo">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-secondary fw-semibold">Contraseña</label>
                                    <input type="password" class="form-control form-control-lg" name="password" required placeholder="Ingresa tu contraseña">
                                </div>
                                <button type="submit" class="btn btn-guinda btn-lg w-100 fw-bold mb-2"><i class="bi bi-box-arrow-in-right me-2"></i>Ingresar a Mis Servicios</button>
                                <p class="text-center text-muted small mt-3">Al ingresar podrás guardar proformas y revisar tus servicios activos.</p>
                            </form>
                        </div>

                        <!-- Panel de Registro -->
                        <div class="tab-pane fade <?php echo isset($_SESSION['show_register_modal']) ? 'show active' : ''; ?> p-4" id="register-panel" role="tabpanel">
                            <?php if (isset($_SESSION['error_registro'])): ?>
                                <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $_SESSION['error_registro'];
    unset($_SESSION['error_registro']); ?></div>
                            <?php
endif; ?>
                            
                            <form action="index.php?controller=auth&action=procesar_registro_cliente" method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-secondary fw-semibold">Primer Nombre</label>
                                        <input type="text" class="form-control" name="p_nombre" required placeholder="Ej: Juan">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-secondary fw-semibold">Segundo Nombre</label>
                                        <input type="text" class="form-control" name="s_nombre" placeholder="Opcional">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-secondary fw-semibold">Apellido Paterno</label>
                                        <input type="text" class="form-control" name="a_paterno" required placeholder="Ej: Perez">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-secondary fw-semibold">Apellido Materno</label>
                                        <input type="text" class="form-control" name="a_materno" required placeholder="Ej: Gomez">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-secondary fw-semibold">DNI</label>
                                        <input type="text" class="form-control" name="dni" required pattern="\d{8}" title="Debe contener 8 dígitos numéricos" placeholder="8 dígitos">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-secondary fw-semibold">Teléfono</label>
                                        <input type="text" class="form-control" name="telefono" placeholder="Opcional">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary fw-semibold">Correo Electrónico</label>
                                    <input type="email" class="form-control" name="email" required placeholder="tu@correo.com">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-secondary fw-semibold">Crea una Contraseña</label>
                                    <input type="password" class="form-control" name="password" required placeholder="Mínimo 6 caracteres">
                                </div>
                                <button type="submit" class="btn btn-guinda btn-lg w-100 fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Registrarme y Entrar</button>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there is a session error flag to reopen modal natively
            <?php if (isset($_SESSION['show_login_modal']) || isset($_SESSION['show_register_modal'])): ?>
                var myModal = new bootstrap.Modal(document.getElementById('clientLoginModal'));
                myModal.show();
                <?php unset($_SESSION['show_login_modal']); ?>
                <?php unset($_SESSION['show_register_modal']); ?>
            <?php
endif; ?>
        });
    </script>
</body>
</html>
