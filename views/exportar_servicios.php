<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../models/Reporte.php';

// Solo Gerentes pueden exportar
AuthMiddleware::checkRole(['Gerente', 'Admin']);

$sede_id = isset($_GET['sede_id']) && $_GET['sede_id'] !== '' ? (int)$_GET['sede_id'] : null;
$fecha_inicio = isset($_GET['fecha_inicio']) && $_GET['fecha_inicio'] !== '' ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) && $_GET['fecha_fin'] !== '' ? $_GET['fecha_fin'] : null;

$reporteModel = new Reporte();
$servicios = $reporteModel->getListaServicios($sede_id, $fecha_inicio, $fecha_fin);

// Headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=reporte_servicios_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');

// UTF-8 BOM para que Excel lea las tildes correctamente
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Encabezados de columnas
fputcsv($output, array('ID', 'DIFUNTO', 'SEDE', 'TIPO SERVICIO', 'ESTADO', 'PRECIO (S/.)', 'FECHA'));

if (count($servicios) > 0) {
    foreach ($servicios as $row) {
        fputcsv($output, array(
            $row['id'],
            $row['difunto_apellidos'] . ' ' . $row['difunto_nombres'],
            $row['sede_nombre'],
            $row['tipo_servicio'],
            strtoupper($row['estado']),
            number_format($row['precio'], 2, '.', ''),
            $row['fecha_servicio']
        ));
    }
}
else {
    fputcsv($output, array('No se encontraron registros para los filtros seleccionados.'));
}

fclose($output);
exit();
?>
