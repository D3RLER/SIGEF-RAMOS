<?php
require_once __DIR__ . '/../models/Reporte.php';

class DashboardController
{

    public function getDashboardData($sede_id = null, $fecha_inicio = null, $fecha_fin = null)
    {
        // Forzar charset utf8 a la salida visual desde el contolador (Prevencion)
        header('Content-Type: text/html; charset=utf-8');

        // Identificacion de alcance segun Rol
        if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'Admin') {
            // Unidades regionales (Gerente, Operario, Vendedor) siempre quedan atrapadas en su Sede
            $sede_id = isset($_SESSION['sede_id']) ? $_SESSION['sede_id'] : 1;
        }
        else {
            // El Admin puede moverse entre Sedes o ver Todas (Valor NULL si esta vacio o no se envia)
            $sede_id = isset($_GET['sede_id']) && $_GET['sede_id'] !== '' ? (int)$_GET['sede_id'] : null;
        }

        $reporteModel = new Reporte();

        // Obtener el nombre de la Sede Activa para Claridad UX
        $sede_nombre_activa = "Todas las Sedes (Global)"; // Por defecto para Admins sin filtro
        if ($sede_id !== null) {
            require_once __DIR__ . '/../models/Sede.php';
            $sedeModel = new Sede();
            $sedeInfo = $sedeModel->getById($sede_id);
            if ($sedeInfo) {
                $sede_nombre_activa = $sedeInfo['nombre'];
            }
        }

        $data = [];
        $data['sede_nombre_activa'] = $sede_nombre_activa;
        $data['ventas_mes'] = $reporteModel->getVentasMes($sede_id, $fecha_inicio, $fecha_fin);
        $data['servicios_activos'] = $reporteModel->getServiciosActivos($sede_id);
        $data['usuarios_conectados'] = $reporteModel->getUsuariosConectados($sede_id);
        $data['stock_critico'] = $reporteModel->getStockCritico($sede_id);
        $data['ultimos_contratos'] = $reporteModel->getUltimosContratos(5, $sede_id);

        // Datos para el grafico Chart.js
        $servicios_mes = $reporteModel->getServiciosPorMes($sede_id);
        $data['chart_labels'] = [];
        $data['chart_data'] = [];
        foreach ($servicios_mes as $row) {
            $data['chart_labels'][] = $row['mes'];
            $data['chart_data'][] = $row['total'];
        }

        return $data;
    }
}
?>
