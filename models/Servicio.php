<?php
require_once __DIR__ . '/../config/conexion.php';

class Servicio
{
    private $conn;

    public function __construct()
    {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    // Obtener servicios del dia actual que no esten finalizados ni cancelados
    public function getServiciosHoy($sede_id = null)
    {
        // En una funeraria los servicios pueden durar mas de 1 dia, pero para la vista operario traemos 
        // los que aun estan en curso (pendiente, en_preparacion, en_traslado)
        $query = "SELECT s.id, s.tipo_servicio, s.descripcion, s.estado, s.fecha_servicio,
                         d.nombres as difunto_nombres, d.apellidos as difunto_apellidos,
                         se.nombre as sede_nombre, se.id as sede_id
                  FROM servicios s
                  JOIN difuntos d ON s.difunto_id = d.id
                  JOIN sedes se ON s.sede_id = se.id
                  WHERE s.estado IN ('pendiente', 'en_preparacion', 'en_traslado') ";

        if ($sede_id) {
            $query .= " AND s.sede_id = :sede_id ";
        }

        $query .= " ORDER BY s.id DESC";

        $stmt = $this->conn->prepare($query);
        if ($sede_id) {
            $stmt->bindParam(':sede_id', $sede_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener servicios FINALIZADOS (para poder revertirlos)
    public function getServiciosFinalizados($sede_id = null)
    {
        $query = "SELECT s.id, s.tipo_servicio, s.descripcion, s.estado, s.fecha_servicio, s.fecha_finalizacion,
                         d.nombres as difunto_nombres, d.apellidos as difunto_apellidos,
                         se.nombre as sede_nombre, se.id as sede_id
                  FROM servicios s
                  JOIN difuntos d ON s.difunto_id = d.id
                  JOIN sedes se ON s.sede_id = se.id
                  WHERE s.estado = 'finalizado' ";

        if ($sede_id) {
            $query .= " AND s.sede_id = :sede_id";
        }

        $query .= " ORDER BY s.fecha_finalizacion DESC, s.id DESC"; // Ultimos finalizados al principio

        $stmt = $this->conn->prepare($query);
        if ($sede_id) {
            $stmt->bindParam(':sede_id', $sede_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar el estado de un servicio
    public function updateEstado($id, $nuevo_estado)
    {
        if ($nuevo_estado === 'finalizado') {
            $query = "UPDATE servicios SET estado = :estado, fecha_finalizacion = NOW() WHERE id = :id";
        }
        else {
            $query = "UPDATE servicios SET estado = :estado, fecha_finalizacion = NULL WHERE id = :id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $nuevo_estado);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
