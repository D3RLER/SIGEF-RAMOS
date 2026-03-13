<?php
require_once __DIR__ . '/../config/conexion.php';

class Difunto
{
    private $conn;
    private $table_name = "difuntos";

    public function __construct()
    {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    // Devuelve la conexion para su uso en transacciones externas (Controlador)
    public function getConnection()
    {
        return $this->conn;
    }
}
?>
