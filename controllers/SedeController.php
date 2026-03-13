<?php
require_once __DIR__ . '/../models/Sede.php';

class SedeController
{
    private $model;

    public function __construct()
    {
        // Solo el Gerente puede administrar sedes
        if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['Gerente', 'Admin'])) {
            header("Location: views/login.php");
            exit();
        }
        $this->model = new Sede();
    }

    public function index()
    {
        $sedes = $this->model->getAll();
        require_once __DIR__ . '/../views/gerente/sedes.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => trim($_POST['nombre']),
                'direccion' => trim($_POST['direccion']),
                'telefono' => trim($_POST['telefono'])
            ];

            if (!empty($_POST['id'])) {
                // Actualizar
                $data['id'] = $_POST['id'];
                $this->model->update($data);
                header("Location: index.php?controller=sede&success=actualizado");
            }
            else {
                // Crear
                $this->model->create($data);
                header("Location: index.php?controller=sede&success=creado");
            }
            exit();
        }
    }

    public function eliminar()
    {
        if (isset($_GET['id'])) {
            try {
                $this->model->delete($_GET['id']);
                header("Location: index.php?controller=sede&success=eliminado");
            }
            catch (Exception $e) {
                // Error de constraint fk
                header("Location: index.php?controller=sede&error=constraint");
            }
            exit();
        }
    }
}
?>
