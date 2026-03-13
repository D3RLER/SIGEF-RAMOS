<?php
require_once __DIR__ . '/../config/conexion.php';

class Cliente
{
    private $conn;

    public function __construct()
    {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    // Registrar nuevo cliente
    public function registrar($p_nombre, $s_nombre, $a_paterno, $a_materno, $dni, $email, $password)
    {
        try {
            $query = "INSERT INTO clientes (p_nombre, s_nombre, a_paterno, a_materno, dni, email, password) VALUES (:p_nombre, :s_nombre, :a_paterno, :a_materno, :dni, :email, :password)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':p_nombre', $p_nombre);
            $stmt->bindParam(':s_nombre', $s_nombre);
            $stmt->bindParam(':a_paterno', $a_paterno);
            $stmt->bindParam(':a_materno', $a_materno);
            $stmt->bindParam(':dni', $dni);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            return $stmt->execute();
        }
        catch (PDOException $e) {
            // Manejar si hay DNI o Email duplicado
            error_log('Error registrando cliente: ' . $e->getMessage());
            return false;
        }
    }

    // Buscar cliente por email o DNI para login
    public function login($identificador, $password)
    {
        $query = "SELECT *, CONCAT_WS(' ', p_nombre, NULLIF(s_nombre, ''), a_paterno, a_materno) AS nombre_completo FROM clientes WHERE email = :id OR dni = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $identificador);
        $stmt->execute();

        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente && password_verify($password, $cliente['password'])) {
            return $cliente;
        }
        return false;
    }

    // Obtener datos del cliente por UID
    public function getById($id)
    {
        $query = "SELECT id, CONCAT_WS(' ', p_nombre, NULLIF(s_nombre, ''), a_paterno, a_materno) AS nombre_completo, p_nombre, a_paterno, dni, email, telefono, direccion FROM clientes WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
