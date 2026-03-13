<?php
require_once __DIR__ . '/../models/Servicio.php';

class ClienteController
{
    public function __construct()
    {
        // Validacion estricta de rol
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Cliente') {
            header("Location: /SIGEF-RAMOS/views/login.php");
            exit();
        }
    }

    public function index()
    {
        $dni_cliente = $_SESSION['dni'] ?? '';

        // Cargar modelo Servicio temporalmente para consultas directas
        $database = new Conexion();
        $conn = $database->getConexion();

        // Obtener "Mis Servicios" (Servicios donde el titular / deudo tiene el mismo DNI que el cliente)
        $query = "SELECT s.*, d.nombres as difunto_nombres, d.apellidos as difunto_apellidos, se.nombre as sede_nombre 
                  FROM servicios s
                  JOIN difuntos d ON s.difunto_id = d.id
                  JOIN deudos de ON d.deudo_id = de.id
                  JOIN sedes se ON s.sede_id = se.id
                  WHERE de.dni = :dni
                  ORDER BY s.fecha_servicio DESC";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':dni', $dni_cliente);
        $stmt->execute();
        $mis_servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/cliente/dashboard.php';
    }

    public function nueva_proforma()
    {
        // Mostrar vista para cotizar
        require_once __DIR__ . '/../views/cliente/nueva_proforma.php';
    }

    public function generarProforma()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo_ataud = trim($_POST['tipo_ataud'] ?? 'Estandar');
            $movilidad = isset($_POST['movilidad']) ? 'Si' : 'No';
            $capilla = isset($_POST['capilla']) ? 'Si' : 'No';
            $cremacion = isset($_POST['cremacion']) ? 'Si' : 'No';

            // Simulacion simple de calculo
            $total = 1500; // Base
            if ($tipo_ataud === 'Lujo')
                $total += 2000;
            if ($tipo_ataud === 'Madera Fina')
                $total += 1000;
            if ($movilidad === 'Si')
                $total += 300;
            if ($capilla === 'Si')
                $total += 800;
            if ($cremacion === 'Si')
                $total += 2500;

            // Generar PDF usando FPDF
            require_once __DIR__ . '/../libs/fpdf.php';

            // Limpiar buffer
            if (ob_get_length())
                ob_end_clean();

            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);

            // Membrete
            $pdf->SetTextColor(109, 0, 50); // Guinda Institucional
            $pdf->Cell(0, 10, utf8_decode('Funeraria RAMOS S.A.C'), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell(0, 5, utf8_decode('"Acompanando con respeto y dignidad"'), 0, 1, 'C');
            $pdf->Ln(10);

            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, utf8_decode('PROFORMA DE SERVICIOS FUNERARIOS'), 0, 1, 'C');
            $pdf->Ln(5);

            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(50, 8, utf8_decode('Cliente Fecha:'), 0, 0);
            $pdf->Cell(0, 8, date('d/m/Y H:i'), 0, 1);
            $pdf->Cell(50, 8, utf8_decode('Solicitante:'), 0, 0);
            $pdf->Cell(0, 8, utf8_decode($_SESSION['nombre']), 0, 1);
            $pdf->Ln(10);

            // Detalles
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetFillColor(230, 230, 230);
            $pdf->Cell(140, 10, 'Concepto', 1, 0, 'C', true);
            $pdf->Cell(50, 10, 'Subtotal', 1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(140, 10, utf8_decode("Servicio Base (Gestion y Tramites)"), 1);
            $pdf->Cell(50, 10, 'S/. 1500.00', 1, 1, 'R');

            $pdf->Cell(140, 10, utf8_decode("Tipo de Ataud: " . $tipo_ataud), 1);
            $pdf->Cell(50, 10, 'Variable', 1, 1, 'R');

            $pdf->Cell(140, 10, utf8_decode("Movilidad/Carroza: " . $movilidad), 1);
            $pdf->Cell(50, 10, 'Variable', 1, 1, 'R');

            $pdf->Cell(140, 10, utf8_decode("Capilla Ardiente: " . $capilla), 1);
            $pdf->Cell(50, 10, 'Variable', 1, 1, 'R');

            $pdf->Cell(140, 10, utf8_decode("Cremacion: " . $cremacion), 1);
            $pdf->Cell(50, 10, 'Variable', 1, 1, 'R');

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(109, 0, 50);
            $pdf->Cell(140, 10, 'TOTAL PRESUPUESTADO ESTIMADO', 1);
            $pdf->Cell(50, 10, 'S/. ' . number_format($total, 2), 1, 1, 'R');

            $pdf->Ln(20);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->SetFont('Arial', 'I', 10);
            $pdf->MultiCell(0, 5, utf8_decode("NOTA: Este documento es una proforma y no representa un contrato de ventas final. Los precios estan sujetos a variaciones de stock en las sedes Ilo y Moquegua. Para hacer efectivo este servicio, acerquese a nuestros asesores."));

            $pdf->Output('I', 'Proforma_Ramos.pdf');
            exit();
        }
    }
}
?>
