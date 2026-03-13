<?php
class Conexion
{
    // Usamos getenv para leer las variables de la nube, 
    // y si no existen (como en tu PC), usa los valores de XAMPP por defecto.
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    public function __construct()
    {
        $this->host = getenv('MYSQLHOST') ?: "localhost";
        $this->db_name = getenv('MYSQLDATABASE') ?: "sigef_ramos";
        $this->username = getenv('MYSQLUSER') ?: "root";
        $this->password = getenv('MYSQLPASSWORD') ?: "";
        $this->port = getenv('MYSQLPORT') ?: "3306";
    }

    public function getConexion()
    {
        $this->conn = null;

        try {
            // Añadimos el host y el puerto para asegurar la conexión en la nube
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";

            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET NAMES utf8mb4");
        }
        catch (PDOException $exception) {
            // En producción es mejor no mostrar el error completo, pero para pruebas está bien
            echo "Error de conexion: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>