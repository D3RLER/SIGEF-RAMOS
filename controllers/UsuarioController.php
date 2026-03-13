<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Sede.php';

class UsuarioController
{
    private $model;

    public function __construct()
    {
        if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['Gerente', 'Admin'])) {
            header("Location: index.php");
            exit();
        }
        $this->model = new Usuario();
    }

    public function index()
    {
        $usuarios = $this->model->getAll();

        $sedeModel = new Sede();
        $sedes = $sedeModel->getAll();

        require_once __DIR__ . '/../views/gerente/usuarios.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => trim($_POST['nombre']),
                'username' => trim($_POST['username']),
                'password' => $_POST['password'] ?? '',
                'rol' => $_POST['rol'],
                'sede_id' => !empty($_POST['sede_id']) ? $_POST['sede_id'] : null
            ];

            try {
                if (!empty($_POST['id'])) {
                    $data['id'] = $_POST['id'];
                    $this->model->update($data);
                    header("Location: index.php?controller=usuario&success=actualizado");
                }
                else {
                    $this->model->create($data);
                    header("Location: index.php?controller=usuario&success=creado");
                }
            }
            catch (Exception $e) {
                // Posible duplicado de username
                header("Location: index.php?controller=usuario&error=duplicado");
            }
            exit();
        }
    }

    public function eliminar()
    {
        if (isset($_GET['id']) && $_GET['id'] != $_SESSION['id']) {
            $this->model->delete($_GET['id']);
            header("Location: index.php?controller=usuario&success=eliminado");
        }
        else {
            // No permitir eliminar al propio gerente en sesion
            header("Location: index.php?controller=usuario&error=propio");
        }
        exit();
    }
}
?>
