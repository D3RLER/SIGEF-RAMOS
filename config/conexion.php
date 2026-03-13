<?php

class Conexion
{
    private $host = "localhost";
    private $db_name = "sigef_ramos";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConexion()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET NAMES utf8mb4");
        }
        catch (PDOException $exception) {
            echo "Error de conexion: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
