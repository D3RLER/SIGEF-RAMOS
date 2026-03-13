<?php

class AuthMiddleware
{
    /**
     * Verifica si el usuario tiene una sesion activa y si su rol coincide con los permitidos.
     * Si no, redirige con un error o al login.
     * 
     * @param array|string $allowedRoles Roles permitidos (ej. 'Gerente' o ['Gerente', 'Vendedor'])
     */
    public static function checkRole($allowedRoles)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si no esta logueado, enviar al login
        if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
            header("Location: /SIGEF-RAMOS/views/login.php");
            exit();
        }

        $userRole = $_SESSION['rol'];

        // Convertir string a array si se paso un solo rol
        if (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }

        // Bypass global: El 'Admin' tiene autoridad maestra en módulos del negocio (Gerente, Operario, Vendedor)
        if ($userRole === 'Admin' && !in_array('Cliente', $allowedRoles)) {
            return;
        }

        // Si el rol del usuario no esta en la lista de permitidos
        if (!in_array($userRole, $allowedRoles)) {
            // Mandarlo al index para que el enrutador lo envie a su dashboard correcto
            header("Location: /SIGEF-RAMOS/index.php");
            exit();
        }
    }

    /**
     * Previene que usuarios ya logueados entren al login nuevamente
     */
    public static function redirectIfAuthenticated()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['id']) && isset($_SESSION['rol'])) {
            header("Location: /SIGEF-RAMOS/index.php");
            exit();
        }
    }
}
?>
