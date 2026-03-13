<?php
require_once __DIR__ . '/../models/Inventario.php';

class InventarioController
{
    private $model;

    public function __construct()
    {
        // Solo el Gerente puede administrar inventario
        if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['Gerente', 'Admin'])) {
            header("Location: index.php");
            exit();
        }
        $this->model = new Inventario();
    }

    public function handleRequest()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        switch ($action) {
            case 'index':
                $this->index();
                break;
            case 'agregar':
                $this->agregar();
                break;
            case 'actualizar':
                $this->actualizar();
                break;
            case 'eliminar':
                $this->eliminar();
                break;
            default:
                $this->index();
                break;
        }
    }

    public function index()
    {
        $sede_id = isset($_GET['sede_id']) ? $_GET['sede_id'] : (isset($_SESSION['sede_id']) ? $_SESSION['sede_id'] : 1);

        // Si no es admin y quiere ver algo que no es su sede, lo forzamos
        if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'Admin') {
            $sede_id = isset($_SESSION['sede_id']) ? $_SESSION['sede_id'] : 1;
        }

        // Si el modelo lo requiere, puedes pasar el $sede_id
        $productos = $this->model->obtenerTodos($sede_id); // Modificar 'obtenerTodos' en el modelo
        require_once __DIR__ . '/../views/gerente/inventario.php';
    }

    public function agregar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'sede_id' => $_SESSION['sede_id'] ?? 1,
                'producto' => $_POST['producto'],
                'categoria' => $_POST['categoria'],
                'stock' => $_POST['stock'] ?? 100,
                'stock_minimo' => $_POST['stock_minimo'] ?? 5,
                'precio' => $_POST['precio']
            ];

            $imagen_path = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                // Generar nombre unico y mover
                $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('img_') . '.' . $ext;
                $upload_dir = __DIR__ . '/../public/img/servicios/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_dir . $filename)) {
                    $imagen_path = 'public/img/servicios/' . $filename;
                }
            }

            $this->model->agregar(
                $data['sede_id'],
                $data['producto'],
                $data['categoria'],
                $data['stock'],
                $data['stock_minimo'],
                $data['precio'], // precio_compra (simplified to take 'precio')
                $data['precio'], // precio_venta (simplified)
                $imagen_path
            );

            if (isset($_POST['source']) && $_POST['source'] === 'cotizador') {
                header("Location: index.php?controller=public&action=cotizador&success=1");
            }
            else {
                header("Location: index.php?controller=inventario&success=1");
            }
            exit();
        }
    }

    public function eliminar()
    {
        if (isset($_GET['id'])) {
            $this->model->eliminar($_GET['id']);

            if (isset($_GET['source']) && $_GET['source'] === 'cotizador') {
                header("Location: index.php?controller=public&action=cotizador&removed=1");
            }
            else {
                header("Location: index.php?controller=inventario&removed=1");
            }
            exit();
        }
    }

    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            // Cargar item actual para mantener imagen o valores por defecto
            $producto_actual = $this->model->obtenerPorId($id);

            $data = [
                'sede_id' => $_SESSION['sede_id'] ?? $producto_actual['sede_id'],
                'producto' => $_POST['producto'],
                'categoria' => $_POST['categoria'],
                'stock' => $_POST['stock'] ?? $producto_actual['stock'],
                'stock_minimo' => $_POST['stock_minimo'] ?? $producto_actual['stock_minimo'],
                'precio' => $_POST['precio']
            ];

            $imagen_path = null; // En actualizar() del modelo, si es null no sobreescribe la imagen anterior
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('img_') . '.' . $ext;
                $upload_dir = __DIR__ . '/../public/img/servicios/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_dir . $filename)) {
                    $imagen_path = 'public/img/servicios/' . $filename;
                }
            }

            $this->model->actualizar(
                $id,
                $data['sede_id'],
                $data['producto'],
                $data['categoria'],
                $data['stock'],
                $data['stock_minimo'],
                $data['precio'],
                $data['precio'],
                $imagen_path
            );

            if (isset($_POST['source']) && $_POST['source'] === 'cotizador') {
                header("Location: index.php?controller=public&action=cotizador&updated=1");
            }
            else {
                header("Location: index.php?controller=inventario&updated=1");
            }
            exit();
        }
    }
}
