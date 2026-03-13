<?php
require_once __DIR__ . '/../config/conexion.php';

class Sede
{
    private $conn;

    public function __construct()
    {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    public function getAll()
    {
        $query = "SELECT * FROM sedes ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM sedes WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO sedes (nombre, direccion, telefono) VALUES (:nombre, :direccion, :telefono)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':telefono', $data['telefono']);
        return $stmt->execute();
    }

    public function update($data)
    {
        $query = "UPDATE sedes SET nombre = :nombre, direccion = :direccion, telefono = :telefono WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        // Al eliminar una sede, si hay usuarios/inventarios vinculados podria fallar por Foreign Keys
        // dependiendo de ON DELETE (en inventario es CASCADE, en usuarios es SET NULL, en servicios es RESTRICT)
        // Por seguridad en este proyecto usaremos Try/Catch en el Controlador.
        $query = "DELETE FROM sedes WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
