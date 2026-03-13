<?php
// Script de Reseteo Visual de Usuarios para SIGEF-RAMOS
// Este script purga la tabla de usuarios y recrea las cuentas base.

require_once __DIR__ . '/config/conexion.php';

$mensaje = "";
$database = new Conexion();
$conn = $database->getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resetear'])) {
    try {
        // 1. Desactivar temporalmente restriccion de claves foraneas
        $conn->exec("SET FOREIGN_KEY_CHECKS = 0;");

        // 2. Vaciar tabla de usuarios (truncar)
        $conn->exec("TRUNCATE TABLE usuarios;");

        // 3. Reactivar restricciones
        $conn->exec("SET FOREIGN_KEY_CHECKS = 1;");

        $mensaje .= "<div class='alert alert-info'><i class='bi bi-info-circle-fill me-2'></i> Tabla <b>usuarios</b> purgada correctamente. Cuentas anteriores eliminadas.</div>";

        // 4. Definir las nuevas cuentas base
        $usuarios_base = [
            [
                'nombre' => 'Administrador Principal',
                'username' => 'admin',
                'password' => 'admin123',
                'rol' => 'Gerente', // En lugar de Admin, forzamos 'Gerente' para evitar cualquier conflicto de roles extranos
                'sede_id' => 1
            ],
            [
                'nombre' => 'Gerente General Ilo',
                'username' => 'gerente_ilo',
                'password' => 'ramos2026',
                'rol' => 'Gerente',
                'sede_id' => 1
            ],
            [
                'nombre' => 'Vendedor Moquegua',
                'username' => 'vendedor_moq',
                'password' => 'ventas2026',
                'rol' => 'Vendedor',
                'sede_id' => 2
            ],
            [
                'nombre' => 'Operario Ilo',
                'username' => 'operario_ilo',
                'password' => 'staff2026',
                'rol' => 'Operario',
                'sede_id' => 1
            ]
        ];

        // 5. Insercion con Hash
        $query = "INSERT INTO usuarios (nombre, username, password, rol, sede_id) VALUES (:nombre, :username, :password, :rol, :sede_id)";
        $stmt = $conn->prepare($query);

        $insertados = 0;
        foreach ($usuarios_base as $u) {
            $hash = password_hash($u['password'], PASSWORD_BCRYPT);

            $stmt->bindParam(':nombre', $u['nombre']);
            $stmt->bindParam(':username', $u['username']);
            $stmt->bindParam(':password', $hash);
            $stmt->bindParam(':rol', $u['rol']);
            $stmt->bindParam(':sede_id', $u['sede_id'], PDO::PARAM_INT);
            $stmt->execute();
            $insertados++;
        }

        $mensaje .= "<div class='alert alert-success'><i class='bi bi-check-circle-fill me-2'></i> ¡Exito! $insertados usuarios han sido re-generados y encriptados correctamente.</div>";

    }
    catch (Exception $e) {
        $mensaje .= "<div class='alert alert-danger'><i class='bi bi-exclamation-triangle-fill me-2'></i> <b>Error:</b> " . $e->getMessage() . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseteo de Credenciales - SIGEF-RAMOS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .reset-card { max-width: 600px; margin: 50px auto; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: none; overflow: hidden; }
        .card-header { background-color: #6D0032; color: white; padding: 20px; text-align: center; border-bottom: 4px solid #4a0022; }
    </style>
</head>
<body>

<div class="container">
    <div class="card reset-card">
        <div class="card-header">
            <h3 class="mb-0 fw-bold"><i class="bi bi-shield-lock-fill me-2"></i>Herramienta de Diagnostico</h3>
            <p class="mb-0 text-white-50 small">Restablecimiento Maestro de Hash de Base de Datos</p>
        </div>
        <div class="card-body p-4">
            
            <?php echo $mensaje; ?>

            <p class="text-muted">Al ejecutar esta accion, <strong>todos los accesos actuales se perderan</strong> y la base de datos se poblara exclusivamente con las siguientes credenciales encriptadas bajo el formato <code>PASSWORD_BCRYPT</code> de PHP.</p>
            
            <table class="table table-sm table-bordered mt-3 text-center">
                <thead class="table-light">
                    <tr>
                        <th>Rol</th>
                        <th>Usuario (Username)</th>
                        <th>Contrasena Limpia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><span class="badge bg-danger">Gerente</span></td><td><b>admin</b></td><td>admin123</td></tr>
                    <tr><td><span class="badge bg-danger">Gerente</span></td><td><b>gerente_ilo</b></td><td>ramos2026</td></tr>
                    <tr><td><span class="badge bg-primary">Vendedor</span></td><td><b>vendedor_moq</b></td><td>ventas2026</td></tr>
                    <tr><td><span class="badge bg-success">Operario</span></td><td><b>operario_ilo</b></td><td>staff2026</td></tr>
                </tbody>
            </table>

            <div class="alert alert-warning mt-4 text-center">
                <i class="bi bi-cone-striped d-block fs-1"></i>
                <strong>Advertencia:</strong> Esta herramienta es un inyector directo SQL. Utilizalo solo si las cuentas estan bloqueadas por hash corruptos.
            </div>

            <form method="POST" action="" class="text-center mt-4">
                <button type="submit" name="resetear" value="1" class="btn btn-warning btn-lg fw-bold w-100 shadow-sm" onclick="return confirm('¿Estas 100% seguro de que deseas purgar la tabla de usuarios y resetear el Hash?');">
                    <i class="bi bi-arrow-clockwise me-2"></i> ¡Ejecutar Reconstruccion de Tabla!
                </button>
                
                <a href="views/login.php" class="btn btn-outline-secondary mt-3 w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Ir al Login
                </a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
