<?php
require_once __DIR__ . '/../config/conexion.php';

class Reporte
{
    private $conn;

    public function __construct()
    {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    // 1. Obtener total de ventas en el mes (o rango)
    public function getVentasMes($sede_id = null, $fecha_inicio = null, $fecha_fin = null)
    {
        $query = "SELECT COUNT(*) as total FROM servicios WHERE 1=1";

        if ($sede_id) {
            $query .= " AND sede_id = :sede_id";
        }
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $query .= " AND fecha_servicio BETWEEN :fecha_inicio AND :fecha_fin";
        }

        $stmt = $this->conn->prepare($query);

        if ($sede_id) {
            $stmt->bindParam(':sede_id', $sede_id);
        }
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['total'] : 0;
    }

    // 2. Obtener Servicios Activos
    public function getServiciosActivos($sede_id = null)
    {
        $query = "SELECT COUNT(*) as activos FROM servicios WHERE estado IN ('pendiente', 'en_proceso')";
        if ($sede_id) {
            $query .= " AND sede_id = :sede_id";
        }
        $stmt = $this->conn->prepare($query);
        if ($sede_id) {
            $stmt->bindParam(':sede_id', $sede_id);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['activos'] : 0;
    }

    // 3. Obtener Stock Critico
    public function getStockCritico($sede_id = null)
    {
        $query = "SELECT producto, stock, stock_minimo, s.nombre as sede_nombre 
                  FROM inventario i
                  JOIN sedes s ON i.sede_id = s.id
                  WHERE categoria = 'Ataud' AND stock <= stock_minimo";

        if ($sede_id) {
            $query .= " AND i.sede_id = :sede_id";
        }

        $stmt = $this->conn->prepare($query);
        if ($sede_id) {
            $stmt->bindParam(':sede_id', $sede_id);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Obtener Empleados Activos por Sede
    public function getUsuariosConectados($sede_id = null)
    {
        $query = "SELECT COUNT(*) as conectados FROM usuarios WHERE 1=1";
        if ($sede_id) {
            $query .= " AND sede_id = :sede_id";
        }
        $stmt = $this->conn->prepare($query);
        if ($sede_id) {
            $stmt->bindParam(':sede_id', $sede_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['conectados'] : 0;
    }

    // Obtener ultimos N contratos
    public function getUltimosContratos($limit = 5, $sede_id = null)
    {
        $query = "SELECT s.id, s.tipo_servicio, s.precio, s.estado, s.fecha_servicio, 
                         d.nombres as difunto_nombres, d.apellidos as difunto_apellidos,
                         se.nombre as sede_nombre,
                         v.nombre as vendedor_nombre
                  FROM servicios s
                  JOIN difuntos d ON s.difunto_id = d.id
                  JOIN sedes se ON s.sede_id = se.id
                  LEFT JOIN usuarios v ON s.vendedor_id = v.id
                  WHERE 1=1";

        if ($sede_id) {
            $query .= " AND s.sede_id = :sede_id";
        }

        $query .= " ORDER BY s.fecha_servicio DESC LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        if ($sede_id) {
            $stmt->bindParam(':sede_id', $sede_id, PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener servicios agrupados por mes para Chart.js (ultimos 6 meses)
    public function getServiciosPorMes($sede_id = null)
    {
        $query = "SELECT DATE_FORMAT(fecha_servicio, '%Y-%m') as mes, COUNT(*) as total 
                  FROM servicios 
                  WHERE fecha_servicio >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)";

        if ($sede_id) {
            $query .= " AND sede_id = :sede_id";
        }

        $query .= " GROUP BY mes ORDER BY mes ASC";

        $stmt = $this->conn->prepare($query);
        if ($sede_id) {
            $stmt->bindParam(':sede_id', $sede_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener lista completa de servicios para exportar/imprimir
    public function getListaServicios($sede_id = null, $fecha_inicio = null, $fecha_fin = null)
    {
        $query = "SELECT s.id, d.nombres as difunto_nombres, d.apellidos as difunto_apellidos,
                         se.nombre as sede_nombre, s.tipo_servicio, s.precio, s.estado, s.fecha_servicio
                  FROM servicios s
                  JOIN difuntos d ON s.difunto_id = d.id
                  JOIN sedes se ON s.sede_id = se.id
                  WHERE 1=1";

        if ($sede_id) {
            $query .= " AND s.sede_id = :sede_id";
        }
        if ($fecha_inicio && $fecha_fin) {
            $query .= " AND s.fecha_servicio BETWEEN :fecha_inicio AND :fecha_fin";
        }

        $query .= " ORDER BY s.fecha_servicio DESC";

        $stmt = $this->conn->prepare($query);
        if ($sede_id) {
            $stmt->bindParam(':sede_id', $sede_id);
        }
        if ($fecha_inicio && $fecha_fin) {
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
