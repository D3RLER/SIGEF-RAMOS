<?php
class VendedorController
{
    public function __construct()
    {
        if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['Vendedor', 'Gerente', 'Admin'])) {
            header("Location: index.php");
            exit();
        }
    }

    public function index()
    {
        require_once __DIR__ . '/../views/dashboard_vendedor.php';
    }

    public function registro()
    {
        require_once __DIR__ . '/../views/vendedor/registro_servicio.php';
    }

    public function mis_contratos()
    {
        // Reutilizamos el ReporteController ya que tiene la misma logica base del historial
        // Pero el modelo ya se encarga de forzar que solo vean su sede.
        // Simulamos un requerimiento al reporteController para no duplicar queries complejas.
        require_once __DIR__ . '/ReporteController.php';
        $reporte = new ReporteController();
        $reporte->historial();
    // Nota: Dentro de $reporte->historial() se valida si NO es Admin -> se fuerza WHERE sede_id = $_SESSION['sede_id']. 
    // Por tanto, el Vendedor NO puede ver contratos de otra sede.
    }
}
?>
