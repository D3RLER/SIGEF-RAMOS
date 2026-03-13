<?php
require_once __DIR__ . '/config/conexion.php';

$database = new Conexion();
$conn = $database->getConexion();

echo "Iniciando generador de datos artificiales (Seed)\n";

try {
    $conn->beginTransaction();

    // Arrays de datos falsos
    $nombres = ['Juan', 'Maria', 'Carlos', 'Lucia', 'Pedro', 'Ana', 'Jose', 'Carmen', 'Luis', 'Rosa', 'Miguel', 'Julia'];
    $apellidos = ['Garcia', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Perez', 'Sanchez', 'Ramirez', 'Torres'];
    $causas_muerte = ['Paro Cardiaco', 'Cancer', 'Accidente de Transito', 'Insuficiencia Renal', 'COVID-19', 'Muerte Natural', 'Traumatismo'];
    $tipos_servicio = ['Paquete Basico', 'Paquete Estandar', 'Paquete Premium', 'Servicio de Cremacion', 'Traslado Nacional'];
    $estados = ['finalizado', 'finalizado', 'finalizado', 'en_traslado', 'pendiente', 'en_preparacion']; // Mas peso a 'finalizado'

    // Generar 20 servicios
    for ($i = 0; $i < 20; $i++) {
        // 1. Crear un Deudo
        $dni_deudo = mt_rand(10000000, 99999999);
        $stmt = $conn->prepare("INSERT INTO deudos (dni, nombres, apellidos, telefono, direccion) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $dni_deudo,
            $nombres[array_rand($nombres)],
            $apellidos[array_rand($apellidos)] . ' ' . $apellidos[array_rand($apellidos)],
            '9' . mt_rand(10000000, 99999999),
            'Av. Ficticia ' . mt_rand(100, 999)
        ]);
        $deudo_id = $conn->lastInsertId();

        // 2. Crear un Difunto
        $dni_difunto = mt_rand(10000000, 99999999);
        // Fecha de fallecimiento entre hace 6 meses y hoy
        $dias_atras = mt_rand(0, 180);
        $fecha_fallecimiento = date('Y-m-d', strtotime("-$dias_atras days"));

        $stmt = $conn->prepare("INSERT INTO difuntos (dni, nombres, apellidos, fecha_fallecimiento, causa_muerte, deudo_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $dni_difunto,
            $nombres[array_rand($nombres)],
            $apellidos[array_rand($apellidos)],
            $fecha_fallecimiento,
            $causas_muerte[array_rand($causas_muerte)],
            $deudo_id
        ]);
        $difunto_id = $conn->lastInsertId();

        // 3. Crear el Servicio
        $sede_id = ($i % 2 == 0) ? 1 : 2; // Alternar estrictamente entre Ilo(1) y Moquegua(2) para que existan 10 de cada uno
        // Supongamos que hay un vendedor con ID 2 o lo asignamos directo a uno generico (id = 2 es Ana Vendedora de Ilo, pero para motivos del seed no importa)
        // Buscamos un id de vendedor valido
        $stmtVend = $conn->query("SELECT id FROM usuarios WHERE rol = 'Vendedor' LIMIT 1");
        $vendRow = $stmtVend->fetch(PDO::FETCH_ASSOC);
        $vendedor_id = $vendRow ? $vendRow['id'] : 2; // Fallback to 2

        $tipo_servicio = $tipos_servicio[array_rand($tipos_servicio)];
        $precio = mt_rand(800, 4500) + (mt_rand(0, 99) / 100);
        $estado = $estados[array_rand($estados)];

        // La fecha del servicio es la misma que del fallecimiento o 1 dia despues
        $fecha_servicio = date('Y-m-d H:i:s', strtotime($fecha_fallecimiento . ' + ' . mt_rand(0, 24) . ' hours'));

        $stmt = $conn->prepare("INSERT INTO servicios (difunto_id, sede_id, vendedor_id, tipo_servicio, descripcion, precio, fecha_servicio, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $difunto_id,
            $sede_id,
            $vendedor_id,
            $tipo_servicio,
            "Contrato generado automaticamente por Seed. Paquete: $tipo_servicio",
            $precio,
            $fecha_servicio,
            $estado
        ]);
    }

    $conn->commit();
    echo "¡20 Servicios funerarios interconectados insertados exitosamente!\n";
    echo "Los graficos del dashboard ahora reflejaran datos historicos.\n";

}
catch (Exception $e) {
    $conn->rollBack();
    echo "Error ejecutando el seed: " . $e->getMessage() . "\n";
}
?>
