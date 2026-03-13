<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$controller_name = $_GET['controller'] ?? 'public';

// Si intenta acceder a un controlador privado sin sesion, lo mandamos a la landing page publica
$rutas_publicas = ['auth', 'public'];
if (!isset($_SESSION['id']) && !in_array($controller_name, $rutas_publicas)) {
    header("Location: index.php?controller=public");
    exit();
}

switch ($controller_name) {
    case 'public':
        require_once 'controllers/PublicController.php';
        $controller = new PublicController();
        break;
    case 'auth':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        break;
    case 'difunto':
        require_once 'controllers/DifuntoController.php';
        $controller = new DifuntoController();
        break;
    case 'inventario':
        require_once 'controllers/InventarioController.php';
        $controller = new InventarioController();
        break;
    case 'operario':
        require_once 'controllers/OperarioController.php';
        $controller = new OperarioController();
        break;
    case 'sede':
        require_once 'controllers/SedeController.php';
        $controller = new SedeController();
        break;
    case 'reporte':
        require_once 'controllers/ReporteController.php';
        $controller = new ReporteController();
        break;
    case 'usuario':
        require_once 'controllers/UsuarioController.php';
        $controller = new UsuarioController();
        break;
    case 'vendedor':
        require_once 'controllers/VendedorController.php';
        $controller = new VendedorController();
        break;
    case 'cliente':
        require_once 'controllers/ClienteController.php';
        $controller = new ClienteController();
        break;
    case 'dashboard':
        $rol = $_SESSION['rol'] ?? '';
        if ($rol === 'Admin' || $rol === 'Gerente') {
            require 'views/dashboard_gerente.php';
        }
        elseif ($rol === 'Vendedor') {
            require 'views/dashboard_vendedor.php';
        }
        else {
            header("Location: index.php");
        }
        exit();
    case 'hub':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Admin') {
            require 'views/admin/hub.php';
        }
        else {
            header("Location: index.php");
        }
        exit();
    default:
        $rol = $_SESSION['rol'] ?? '';
        switch ($rol) {
            case 'Admin':
                header("Location: index.php?controller=hub");
                break;
            case 'Gerente':
                header("Location: index.php?controller=dashboard");
                break;
            case 'Vendedor':
                header("Location: index.php?controller=dashboard"); // Panel Ventas
                break;
            case 'Operario':
                header("Location: index.php?controller=operario"); // Logistica
                break;
            case 'Cliente':
                header("Location: index.php?controller=cliente"); // Panel Cliente
                break;
            default:
                // Si no hay sesion o el rol es invalido, mostrar la landing page publica
                header("Location: index.php?controller=public");
                break;
        }
        exit();
}

if (isset($controller)) {
    $action = $_GET['action'] ?? 'index';

    // Para Inventario y otros que usen handleRequest interno u override
    if (method_exists($controller, 'handleRequest') && $action === 'index') {
        $controller->handleRequest();
        exit();
    }

    if (method_exists($controller, $action)) {
        $controller->$action();
        exit();
    }
    else if (method_exists($controller, 'index')) {
        $controller->index();
        exit();
    }
}
?>
