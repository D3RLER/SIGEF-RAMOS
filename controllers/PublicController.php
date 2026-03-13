<?php
class PublicController
{
    public function index()
    {
        // Si hay una sesion activa, el navbar mostrara "Ir a mi Panel" en lugar de "Login"
        require_once __DIR__ . '/../views/public/index.php';
    }

    public function cotizador()
    {
        require_once __DIR__ . '/../models/Inventario.php';
        $inventarioModel = new Inventario();
        // Obtener productos de Ilo por defecto, u ocultar filtro y traer todos si la sede se elige despues
        // Para simplificar, traemos de la sede 1. En la vista la sede puede seleccionarse
        $productosRaw = $inventarioModel->obtenerTodos(1);

        $productos = [
            'Ataudes' => [],
            'Traslados' => [],
            'Salas de Velacion' => [],
            'Arreglos Florales' => [],
            'Recordatorios Funebres' => [],
            'Gestion de Tramites' => []
        ];

        foreach ($productosRaw as $p) {
            $cat = $p['categoria'];
            if (isset($productos[$cat])) {
                $productos[$cat][] = $p;
            }
        }

        require_once __DIR__ . '/../views/public/cotizador.php';
    }

    public function generarProformaPDF()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sede_id = $_POST['sede_id'] ?? 1;
            $items_seleccionados = $_POST['items'] ?? []; // Array de IDs del inventario

            require_once __DIR__ . '/../models/Inventario.php';
            $inventarioModel = new Inventario();

            $total = 0;
            $detalles_productos = [];

            // Recolectar datos reales de la BD
            foreach ($items_seleccionados as $item_id) {
                $prod = $inventarioModel->obtenerPorId($item_id);
                if ($prod) {
                    $total += $prod['precio_venta'];
                    $detalles_productos[] = $prod;
                }
            }

            // Logica Hibrida: Si es cliente, guardarlo en BD (adaptado para guardar descripcion o JSON si es necesario, pero temporalmente guardaremos como 'Cotizacion Dinamica')
            if (session_status() == PHP_SESSION_NONE)
                session_start();
            $cliente_nombre = "Invitado";

            if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Cliente') {
                $cliente_nombre = $_SESSION['nombre'];
                $cliente_id = $_SESSION['id'];

                require_once __DIR__ . '/../config/conexion.php';
                $db = new Conexion();
                $conn = $db->getConexion();

                // Como la DB antigua tenia ataud, movilidad fijos y esto ahora es diamico, guardaremos el total e indicaremos "Proforma Dinamica" en ataud
                $query = "INSERT INTO proformas (cliente_id, sede_id, tipo_ataud, movilidad, capilla, cremacion, total) 
                          VALUES (:cli, :sede, 'Cotizacion Multicategoria', 'No', 'No', 'No', :total)";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':cli', $cliente_id);
                $stmt->bindParam(':sede', $sede_id);
                $stmt->bindParam(':total', $total);
                $stmt->execute();
            }

            // Nombre Sede texto para PDF
            $sede_nombre = ($sede_id == 1) ? 'Ilo' : 'Moquegua';

            // FPDF
            require_once __DIR__ . '/../libs/fpdf.php';
            if (ob_get_length())
                ob_end_clean();
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);

            // Colores Guinda e Impresion
            $pdf->SetTextColor(109, 0, 50);
            $pdf->Cell(0, 10, utf8_decode('Funeraria RAMOS S.A.C'), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell(0, 5, utf8_decode('"Acompanando con respeto y dignidad"'), 0, 1, 'C');
            $pdf->Ln(10);

            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, utf8_decode('PROFORMA OFICIAL DE SERVICIOS'), 0, 1, 'C');
            $pdf->Ln(5);

            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(50, 8, utf8_decode('Solicitante:'), 0, 0);
            $pdf->Cell(0, 8, utf8_decode($cliente_nombre), 0, 1);
            $pdf->Cell(50, 8, utf8_decode('Sede Proyectada:'), 0, 0);
            $pdf->Cell(0, 8, utf8_decode($sede_nombre), 0, 1);
            $pdf->Cell(50, 8, utf8_decode('Fecha Emision:'), 0, 0);
            $pdf->Cell(0, 8, date('d/m/Y H:i'), 0, 1);
            $pdf->Ln(10);

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetFillColor(230, 230, 230);
            $pdf->Cell(140, 10, 'Categoria y Producto', 1, 0, 'C', true);
            $pdf->Cell(50, 10, 'Subtotal', 1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 12);

            if (empty($detalles_productos)) {
                $pdf->Cell(140, 10, 'Ningun servicio seleccionado', 1);
                $pdf->Cell(50, 10, 'S/. 0.00', 1, 1, 'R');
            }
            else {
                foreach ($detalles_productos as $prod) {
                    $cat = utf8_decode($prod['categoria']);
                    $nombre = utf8_decode($prod['producto']);
                    $precio = number_format($prod['precio_venta'], 2);

                    $pdf->Cell(140, 10, "$cat - $nombre", 1);
                    $pdf->Cell(50, 10, "S/. $precio", 1, 1, 'R');
                }
            }

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(109, 0, 50);
            $pdf->Cell(140, 10, 'TOTAL PRESUPUESTADO ESTIMADO', 1);
            $pdf->Cell(50, 10, 'S/. ' . number_format($total, 2), 1, 1, 'R');

            $pdf->Ln(20);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->SetFont('Arial', 'I', 10);
            $pdf->MultiCell(0, 5, utf8_decode("NOTA: Las tarifas expresadas pueden variar ligeramente segun stock local en la sede de $sede_nombre. Para validar esta proforma acerquese con sus asesores."));

            $pdf->Output('I', 'Proforma.pdf');
            exit();
        }
    }
}
?>
