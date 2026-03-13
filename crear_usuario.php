<?php
require_once __DIR__ . '/config/conexion.php';

try {
    $database = new Conexion();
    $conn = $database->getConexion();

    $nombre = "Administrador del Sistema";
    $username = "admin";
    $password_plain = "admin123";
    $password_hash = password_hash($password_plain, PASSWORD_BCRYPT);
    $rol = "Gerente"; // Asignamos Gerente ya que "admin" no esta en el ENUM de db.sql
    $sede_id = 1; // Sede Ilo por defecto

    $query = "INSERT IGNORE INTO usuarios (nombre, username, password, rol, sede_id) VALUES (:nombre, :username, :password, :rol, :sede_id)";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password_hash);
    $stmt->bindParam(':rol', $rol);
    $stmt->bindParam(':sede_id', $sede_id);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            echo "Usuario 'admin' creado exitosamente con la contrasena 'admin123' (asignado como Gerente).";
        }
        else {
            echo "El usuario 'admin' ya existia en la base de datos.";
        }
    }
    else {
        echo "Error al ejecutar la consulta.";
    }
}
catch (Exception $e) {
    echo "Excepcion capturada: " . $e->getMessage();
}
?>
