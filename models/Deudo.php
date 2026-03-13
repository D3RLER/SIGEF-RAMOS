<?php
require_once __DIR__ . '/../config/conexion.php';

class Deudo
{
    private $conn;
    private $table_name = "deudos";

    public function __construct()
    {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    // Busca un deudo por DNI
    public function findByDni($dni)
    {
        $query = "SELECT id, dni, nombres, apellidos, telefono, direccion, email 
                  FROM " . $this->table_name . " WHERE dni = :dni LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':dni', $dni);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
}
?>
