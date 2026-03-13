<?php
require_once __DIR__ . '/../config/conexion.php';

class Usuario
{
    private $conn;
    private $table_name = "usuarios";

    public function __construct()
    {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    public function login($username, $password)
    {
        $query = "SELECT u.id, u.nombre, u.username, u.password, u.rol, u.sede_id, s.nombre as sede_nombre 
                  FROM " . $this->table_name . " u
                  LEFT JOIN sedes s ON u.sede_id = s.id
                  WHERE u.username = :username LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }

    public function getAll($sede_id = null)
    {
        $query = "SELECT u.id, u.nombre, u.username, u.rol, u.sede_id, s.nombre as sede_nombre 
                  FROM usuarios u 
                  LEFT JOIN sedes s ON u.sede_id = s.id ";

        if ($sede_id) {
            $query .= " WHERE u.sede_id = :sede_id ";
        }

        $query .= " ORDER BY u.id ASC";
        $stmt = $this->conn->prepare($query);
        if ($sede_id) {
            $stmt->bindParam(':sede_id', $sede_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT u.id, u.nombre, u.username, u.rol, u.sede_id, s.nombre as sede_nombre 
                  FROM " . $this->table_name . " u
                  LEFT JOIN sedes s ON u.sede_id = s.id
                  WHERE u.id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO usuarios (nombre, username, password, rol, sede_id) 
                  VALUES (:nombre, :username, :password, :rol, :sede_id)";
        $stmt = $this->conn->prepare($query);
        $hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':password', $hash);
        $stmt->bindParam(':rol', $data['rol']);
        $sede_id = !empty($data['sede_id']) ? $data['sede_id'] : null;
        $stmt->bindParam(':sede_id', $sede_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function update($data)
    {
        if (!empty($data['password'])) {
            $query = "UPDATE usuarios SET nombre=:nombre, username=:username, password=:password, rol=:rol, sede_id=:sede_id WHERE id=:id";
            $stmt = $this->conn->prepare($query);
            $hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $hash);
        }
        else {
            $query = "UPDATE usuarios SET nombre=:nombre, username=:username, rol=:rol, sede_id=:sede_id WHERE id=:id";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':rol', $data['rol']);
        $sede_id = !empty($data['sede_id']) ? $data['sede_id'] : null;
        $stmt->bindParam(':sede_id', $sede_id, PDO::PARAM_INT);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
