<?php
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
// Solo los administradores globales (matriz) llegan aqui
AuthMiddleware::checkRole('Admin');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Mando - Administrador Principal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f4f6f9; 
            font-family: 'Roboto', sans-serif; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bg-guinda { background-color: #6D0032 !important; color: white;}
        .text-guinda { color: #6D0032 !important; }
        
        .hub-card { 
            border: none; 
            border-radius: 15px; 
            transition: all 0.3s; 
            cursor: pointer; 
            text-decoration: none; 
            display: block; 
            overflow: hidden; 
            background: #fff;
        }
        .hub-card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 15px 30px rgba(109,0,50,0.15) !important; 
            border-bottom: 4px solid #6D0032;
        }
        
        .hub-icon-wrapper {
            width: 80px;
            height: 80px;
            background-color: rgba(109, 0, 50, 0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
            transition: 0.3s;
        }
        .hub-card:hover .hub-icon-wrapper {
            background-color: #6D0032;
        }
        
        .hub-icon { 
            font-size: 2.5rem; 
            color: #6D0032; 
            transition: 0.3s;
        }
        .hub-card:hover .hub-icon {
            color: white;
        }
        
        .hub-title { color: #212529; font-weight: 700; margin-bottom: 10px; }
        .hub-desc { color: #6c757d; font-size: 0.9rem; margin-bottom: 0; line-height: 1.5; }
        
        .logout-btn { position: absolute; top: 20px; right: 20px; z-index: 1000; }
        .decor-blob { position: absolute; top: -100px; left: -100px; width: 300px; height: 300px; background: rgba(109,0,50,0.05); border-radius: 50%; z-index: -1; }
        .decor-blob-2 { position: absolute; bottom: -150px; right: -50px; width: 400px; height: 400px; background: rgba(109,0,50,0.03); border-radius: 50%; z-index: -1; }
    </style>
</head>
<body class="position-relative overflow-hidden">
    
    <div class="decor-blob"></div>
    <div class="decor-blob-2"></div>
    
    <a href="index.php?controller=auth&action=logout" class="btn btn-outline-danger logout-btn shadow-sm rounded-pill px-4">
        <i class="bi bi-box-arrow-left me-2"></i> Cerrar Sesion
    </a>
    
    <div class="container py-5">
        
        <div class="text-center mb-5">
            <h1 class="fw-bold text-dark display-5 mb-2"><i class="bi bi-shield-lock-fill text-guinda me-3"></i>Centro Global de Mando</h1>
            <p class="text-muted fs-5">Bienvenido, Administrador Principal <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong></p>
            <p class="text-secondary small">Seleccione el ecosistema al que desea acceder con sus privilegios de Bypass.</p>
            <div class="mx-auto bg-guinda mt-4" style="height: 4px; width: 80px; border-radius: 2px;"></div>
        </div>

        <div class="row g-4 justify-content-center">
            
            <!-- Modulo Reportes/Gerencia -->
            <div class="col-md-6 col-lg-3">
                <a href="index.php?controller=dashboard" class="card hub-card h-100 shadow-sm text-center p-4">
                    <div class="card-body p-0">
                        <div class="hub-icon-wrapper">
                            <i class="bi bi-bar-chart-fill hub-icon"></i>
                        </div>
                        <h5 class="hub-title">Gerencia Global</h5>
                        <p class="hub-desc">Estadisticas de ventas, auditorias, sede cruzada, inventario general y rrhh.</p>
                    </div>
                </a>
            </div>

            <!-- Modulo Ventas -->
            <div class="col-md-6 col-lg-3">
                <a href="index.php?controller=vendedor" class="card hub-card h-100 shadow-sm text-center p-4">
                    <div class="card-body p-0">
                        <div class="hub-icon-wrapper">
                            <i class="bi bi-cart-plus-fill hub-icon"></i>
                        </div>
                        <h5 class="hub-title">Modulo Ventas</h5>
                        <p class="hub-desc">Ingresar contratos funebres, emitir proformas y registrar nuevos deudos y difuntos.</p>
                    </div>
                </a>
            </div>

            <!-- Modulo Operario -->
            <div class="col-md-6 col-lg-3">
                <a href="index.php?controller=operario" class="card hub-card h-100 shadow-sm text-center p-4">
                    <div class="card-body p-0">
                        <div class="hub-icon-wrapper">
                            <i class="bi bi-truck hub-icon"></i>
                        </div>
                        <h5 class="hub-title">Logistica y Campo</h5>
                        <p class="hub-desc">Gestionar estados de servicios activos: traslados, carrozas y salas de velacion.</p>
                    </div>
                </a>
            </div>

            <!-- Public Portal -->
            <div class="col-md-6 col-lg-3">
                <a href="index.php" class="card hub-card h-100 shadow-sm text-center p-4" style="border-radius: 15px; border: 2px dashed #6D0032;">
                    <div class="card-body p-0">
                        <div class="hub-icon-wrapper" style="background-color: transparent;">
                            <i class="bi bi-globe2 hub-icon" style="color: #0d6efd;"></i>
                        </div>
                        <h5 class="hub-title text-primary">Portal Publico</h5>
                        <p class="hub-desc">Retornar a la Landing Page para cotizar como invitado y revisar UX visual.</p>
                    </div>
                </a>
            </div>
            
        </div>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
