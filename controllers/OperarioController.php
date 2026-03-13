<?php
require_once __DIR__ . '/../models/Servicio.php';

class OperarioController
{
    private $model;

    public function __construct()
    {
        // Solo el Operario puede entrar a su dashboard (o un admin para pruebas, pero restringido a Operario principalmente)
        // Por seguridad permitimos a Gerente probar la vista.
        if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['Operario', 'Gerente', 'Admin'])) {
            header("Location: /SIGEF-RAMOS/views/login.php");
            exit();
        }
        $this->model = new Servicio();
    }

    public function index()
    {
        $sede_id = (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'Admin') ? $_SESSION['sede_id'] : null;
        // Obtener la lista de servicios activos para mostrarla en el dashboard
        $servicios = $this->model->getServiciosHoy($sede_id);
        // Obtener los finalizados para la seccion inferior
        $servicios_finalizados = $this->model->getServiciosFinalizados($sede_id);

        require_once __DIR__ . '/../views/dashboard_operario.php';
    }

    public function cambiarEstado()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['estado'])) {
            $id = $_POST['id'];
            $nuevo_estado = $_POST['estado'];

            // Validar estados permitidos (incluido pendiente para devoluciones desde finalizado)
            $estados_validos = ['pendiente', 'en_preparacion', 'en_traslado', 'finalizado'];
            if (in_array($nuevo_estado, $estados_validos)) {
                $this->model->updateEstado($id, $nuevo_estado);
                header("Location: index.php?controller=operario&success=estado_actualizado");
            }
            else {
                header("Location: index.php?controller=operario&error=estado_invalido");
            }
            exit();
        }
    }
}
