<?php
require_once __DIR__ . '/../config/conexion.php';

class Inventario
{
    private $conn;
    private $table_name = "inventario";

    public function __construct()
    {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    // Listar todos los productos
    public function obtenerTodos($sede_id = null)
    {
        $query = "SELECT i.id, i.producto, i.categoria, i.imagen, i.stock, i.stock_minimo, i.precio_compra, i.precio_venta, s.nombre as sede_nombre 
                  FROM " . $this->table_name . " i
                  JOIN sedes s ON i.sede_id = s.id";

        if ($sede_id) {
            $query .= " WHERE i.sede_id = :sede_id";
        }

        $query .= " ORDER BY i.categoria, i.producto ASC";

        $stmt = $this->conn->prepare($query);

        if ($sede_id) {
            $stmt->bindParam(':sede_id', $sede_id);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un producto por ID
    public function obtenerPorId($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Agregar nuevo producto
    public function agregar($sede_id, $producto, $categoria, $stock, $stock_minimo, $precio_compra, $precio_venta, $imagen = null)
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (sede_id, producto, categoria, imagen, stock, stock_minimo, precio_compra, precio_venta) 
                  VALUES (:sede_id, :producto, :categoria, :imagen, :stock, :stock_minimo, :precio_compra, :precio_venta)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sede_id', $sede_id);
        $stmt->bindParam(':producto', $producto);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':imagen', $imagen);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':stock_minimo', $stock_minimo);
        $stmt->bindParam(':precio_compra', $precio_compra);
        $stmt->bindParam(':precio_venta', $precio_venta);

        return $stmt->execute();
    }

    // Actualizar producto
    public function actualizar($id, $sede_id, $producto, $categoria, $stock, $stock_minimo, $precio_compra, $precio_venta, $imagen = null)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET sede_id = :sede_id, producto = :producto, categoria = :categoria, 
                      stock = :stock, stock_minimo = :stock_minimo, 
                      precio_compra = :precio_compra, precio_venta = :precio_venta";
        if ($imagen !== null) {
            $query .= ", imagen = :imagen";
        }
        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':sede_id', $sede_id);
        $stmt->bindParam(':producto', $producto);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':stock_minimo', $stock_minimo);
        $stmt->bindParam(':precio_compra', $precio_compra);
        $stmt->bindParam(':precio_venta', $precio_venta);
        if ($imagen !== null) {
            $stmt->bindParam(':imagen', $imagen);
        }

        return $stmt->execute();
    }

    // Eliminar producto
    public function eliminar($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
?>
