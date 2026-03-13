<?php
require_once __DIR__ . '/config/conexion.php';

$database = new Conexion();
$conn = $database->getConexion();

echo "Iniciando reseteo de usuarios...\n";

try {
    // Desactivar temporalmente restriccion de claves foraneas
    $conn->exec("SET FOREIGN_KEY_CHECKS = 0;");

    // Vaciar tabla
    $conn->exec("TRUNCATE TABLE usuarios;");

    // Reactivar restriccion
    $conn->exec("SET FOREIGN_KEY_CHECKS = 1;");

    echo "Tabla usuarios vaciada correctamente.\n";

    $usuarios = [
        [
            'nombre' => 'Administrador Principal',
            'username' => 'admin',
            'password' => 'admin123',
            'rol' => 'Admin', // Espera, el rol 'Admin' no existia inicialmente, pero lo agregamos
            'sede_id' => 1
        ],
        [
            'nombre' => 'Gerente General Ilo',
            'username' => 'gerente_ilo',
            'password' => 'ramos2026',
            'rol' => 'Gerente',
            'sede_id' => 1
        ],
        [
            'nombre' => 'Vendedor Moquegua',
            'username' => 'vendedor_moq',
            'password' => 'ventas2026',
            'rol' => 'Vendedor',
            'sede_id' => 2
        ],
        [
            'nombre' => 'Operario Ilo',
            'username' => 'operario_ilo',
            'password' => 'staff2026',
            'rol' => 'Operario',
            'sede_id' => 1
        ]
    ];

    $query = "INSERT INTO usuarios (nombre, username, password, rol, sede_id) VALUES (:nombre, :username, :password, :rol, :sede_id)";
    $stmt = $conn->prepare($query);

    foreach ($usuarios as $u) {
        $hash = password_hash($u['password'], PASSWORD_BCRYPT);

        $stmt->bindParam(':nombre', $u['nombre']);
        $stmt->bindParam(':username', $u['username']);
        $stmt->bindParam(':password', $hash);
        $stmt->bindParam(':rol', $u['rol']);
        $stmt->bindParam(':sede_id', $u['sede_id'], PDO::PARAM_INT);
        $stmt->execute();

        echo "Usuario '{$u['username']}' insertado exitosamente.\n";
    }

}
catch (Exception $e) {
    echo "Error ejecutando el reseteo: " . $e->getMessage() . "\n";
}
?>
