<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../libs/fpdf.php';

class ReporteController
{
    private $conn;

    public function __construct()
    {
        if (!isset($_SESSION['id'])) {
            header("Location: /SIGEF-RAMOS/views/login.php");
            exit();
        }
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }


    public function index()
    {
        $this->historial();
    }

    public function historial()
    {
        $query = "SELECT s.id, s.tipo_servicio, s.precio, s.fecha_servicio, s.estado,
                         d.nombres as dif_nombres, d.apellidos as dif_apellidos,
                         v.nombre as vendedor_nombre, se.nombre as sede_nombre
                  FROM servicios s
                  JOIN difuntos d ON s.difunto_id = d.id
                  JOIN usuarios v ON s.vendedor_id = v.id
                  JOIN sedes se ON s.sede_id = se.id";

        if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'Admin') {
            $query .= " WHERE s.sede_id = :sede_id";
        }

        $query .= " ORDER BY s.fecha_servicio DESC";

        $stmt = $this->conn->prepare($query);
        if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'Admin') {
            $stmt->bindParam(':sede_id', $_SESSION['sede_id'], PDO::PARAM_INT);
        }
        $stmt->execute();
        $contratos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/gerente/historial.php';
    }

    public function generarContrato()
    {
        if (!isset($_GET['id'])) {
            die("ID de contrato no especificado.");
        }

        $id = $_GET['id'];

        // Obtener datos consolidados del servicio
        $query = "SELECT s.*, 
                         d.dni as dif_dni, d.nombres as dif_nombres, d.apellidos as dif_apellidos, d.causa_muerte,
                         de.dni as deu_dni, de.nombres as deu_nombres, de.apellidos as deu_apellidos, de.telefono as deu_telefono, de.direccion as deu_direccion,
                         se.nombre as sede_nombre,
                         v.nombre as vendedor_nombre
                  FROM servicios s
                  JOIN difuntos d ON s.difunto_id = d.id
                  JOIN deudos de ON d.deudo_id = de.id
                  JOIN sedes se ON s.sede_id = se.id
                  JOIN usuarios v ON s.vendedor_id = v.id
                  WHERE s.id = :id";

        // IDOR Protection: Restrict by sede if not Admin
        if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'Admin') {
            $query .= " AND s.sede_id = :sede_id";
        }
        $query .= " LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'Admin') {
            $stmt->bindParam(':sede_id', $_SESSION['sede_id'], PDO::PARAM_INT);
        }
        $stmt->execute();
        $contrato = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$contrato) {
            die("Contrato no encontrado.");
        }

        // --- INICIO FPDF ---
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetMargins(15, 15, 15);

        // Colores Corporativos (Guinda: 109, 0, 50 aprox para #6D0032)
        $guinda_r = 109;
        $guinda_g = 0;
        $guinda_b = 50;

        // --- HEADER ---
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetTextColor($guinda_r, $guinda_g, $guinda_b);
        $pdf->Cell(0, 10, utf8_decode('FUNERARIA RAMOS S.A.C.'), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(0, 5, utf8_decode('Brindando paz y consuelo en los momentos mas dificiles'), 0, 1, 'C');
        $pdf->Cell(0, 5, utf8_decode('Sede de Atencion: ' . $contrato['sede_nombre']), 0, 1, 'C');
        $pdf->Ln(10);

        // --- TITULO DOCUMENTO ---
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(0, 8, utf8_decode('CONTRATO DE SERVICIOS FUNERARIOS N° ' . str_pad($contrato['id'], 6, '0', STR_PAD_LEFT)), 1, 1, 'C', true);
        $pdf->Ln(5);

        // --- DATOS DEL CONTRATANTE (DEUDO) ---
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor($guinda_r, $guinda_g, $guinda_b);
        $pdf->Cell(0, 6, utf8_decode('1. DATOS DEL CONTRATANTE (FAMILIAR)'), 0, 1, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(40, 6, 'Nombre Completo:', 0, 0);
        $pdf->Cell(0, 6, utf8_decode($contrato['deu_nombres'] . ' ' . $contrato['deu_apellidos']), 0, 1);
        $pdf->Cell(40, 6, 'DNI / Documento:', 0, 0);
        $pdf->Cell(0, 6, utf8_decode($contrato['deu_dni']), 0, 1);
        $pdf->Cell(40, 6, utf8_decode('Telefono:'), 0, 0);
        $pdf->Cell(0, 6, utf8_decode($contrato['deu_telefono']), 0, 1);
        $pdf->Cell(40, 6, utf8_decode('Direccion:'), 0, 0);
        $pdf->Cell(0, 6, utf8_decode($contrato['deu_direccion']), 0, 1);
        $pdf->Ln(5);

        // --- DATOS DEL DIFUNTO ---
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor($guinda_r, $guinda_g, $guinda_b);
        $pdf->Cell(0, 6, utf8_decode('2. DATOS DEL FALLECIDO'), 0, 1, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(40, 6, 'Nombre Completo:', 0, 0);
        $pdf->Cell(0, 6, utf8_decode($contrato['dif_nombres'] . ' ' . $contrato['dif_apellidos']), 0, 1);
        $pdf->Cell(40, 6, 'DNI / Documento:', 0, 0);
        $pdf->Cell(0, 6, utf8_decode($contrato['dif_dni'] ?? 'N/A'), 0, 1);
        $pdf->Cell(40, 6, 'Causa de Muerte:', 0, 0);
        $pdf->Cell(0, 6, utf8_decode($contrato['causa_muerte'] ?? 'N/D'), 0, 1);
        $pdf->Ln(5);

        // --- DETALLE DEL SERVICIO ---
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor($guinda_r, $guinda_g, $guinda_b);
        $pdf->Cell(0, 6, utf8_decode('3. DETALLE DE LOS SERVICIOS CONTRATADOS'), 0, 1, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 10);

        $pdf->Cell(40, 6, 'Fecha del Servicio:', 0, 0);
        $pdf->Cell(0, 6, date('d/m/Y H:i', strtotime($contrato['fecha_servicio'])), 0, 1);

        $pdf->Cell(40, 6, 'Paquete Fijo:', 0, 0);
        $pdf->Cell(0, 6, utf8_decode($contrato['tipo_servicio']), 0, 1);

        $pdf->Cell(40, 6, 'Detalles / Notas:', 0, 0);
        $pdf->MultiCell(0, 6, utf8_decode($contrato['descripcion'] ?? 'Sin observaciones adicionales.'), 0, 'J');
        $pdf->Ln(5);

        // --- LIQUIDACION ECONOMICA ---
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor($guinda_r, $guinda_g, $guinda_b);
        $pdf->Cell(0, 6, utf8_decode('4. RESUMEN ECONOMICO'), 0, 1, 'L');
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(130, 8, utf8_decode('TOTAL A PAGAR (INCLUYE IGV):'), 1, 0, 'R');
        $pdf->SetTextColor($guinda_r, $guinda_g, $guinda_b);
        $pdf->Cell(0, 8, 'S/ ' . number_format($contrato['precio'], 2), 1, 1, 'C');

        $pdf->Ln(20);

        // --- FIRMAS ---
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(85, 5, '___________________________________', 0, 0, 'C');
        $pdf->Cell(10, 5, '', 0, 0);
        $pdf->Cell(85, 5, '___________________________________', 0, 1, 'C');

        $pdf->Cell(85, 5, utf8_decode('Firma del Contratante'), 0, 0, 'C');
        $pdf->Cell(10, 5, '', 0, 0);
        $pdf->Cell(85, 5, utf8_decode('Firma de la Empresa'), 0, 1, 'C');

        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(85, 5, utf8_decode('DNI: ' . $contrato['deu_dni']), 0, 0, 'C');
        $pdf->Cell(10, 5, '', 0, 0);
        $pdf->Cell(85, 5, utf8_decode('Atendido por: ' . $contrato['vendedor_nombre']), 0, 1, 'C');

        // Salida
        if (ob_get_length()) {
            ob_end_clean();
        }
        $pdf->Output('I', 'Contrato_Ramos_' . $contrato['id'] . '.pdf');
    }
}
?>
