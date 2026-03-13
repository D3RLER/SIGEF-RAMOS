<?php
require_once __DIR__ . '/../models/Deudo.php';
require_once __DIR__ . '/../models/Difunto.php';
require_once __DIR__ . '/../config/Conexion.php'; // Added for the new method

class DifuntoController
{

    // Metodo AJAX para buscar Deudo por DNI
    public function buscarDeudo($dni)
    {
        $deudoModel = new Deudo();
        $resultado = $deudoModel->findByDni($dni);

        header('Content-Type: application/json');
        if ($resultado) {
            echo json_encode(['status' => 'success', 'data' => $resultado]);
        }
        else {
            echo json_encode(['status' => 'not_found']);
        }
        exit();
    }

    // Registro maestro de Deudo y Difunto
    public function registrar($data)
    {
        $difuntoModel = new Difunto();
        $conn = $difuntoModel->getConnection();

        try {
            // Iniciar Transaccion
            $conn->beginTransaction();

            $deudo_id = null;

            // 1. Manejar el Deudo (Verificar si existe o crearlo)
            if (!empty($data['deudo_id_existente'])) {
                $deudo_id = $data['deudo_id_existente'];
            }
            else {
                // Insertar nuevo Deudo
                $queryDeudo = "INSERT INTO deudos (dni, nombres, apellidos, telefono, direccion, email) 
                               VALUES (:dni, :nombres, :apellidos, :telefono, :direccion, :email)";
                $stmtDeudo = $conn->prepare($queryDeudo);
                $stmtDeudo->bindParam(':dni', $data['deudo_dni']);
                $stmtDeudo->bindParam(':nombres', $data['deudo_nombres']);
                $stmtDeudo->bindParam(':apellidos', $data['deudo_apellidos']);
                $stmtDeudo->bindParam(':telefono', $data['deudo_telefono']);
                $stmtDeudo->bindParam(':direccion', $data['deudo_direccion']);
                $stmtDeudo->bindParam(':email', $data['deudo_email']);

                if (!$stmtDeudo->execute()) {
                    throw new Exception("Error al registrar el Deudo.");
                }
                $deudo_id = $conn->lastInsertId();
            }

            // 2. Insertar el Difunto
            $queryDifunto = "INSERT INTO difuntos (dni, nombres, apellidos, fecha_nacimiento, fecha_fallecimiento, causa_muerte, deudo_id) 
                             VALUES (:dni, :nombres, :apellidos, :fecha_nacimiento, :fecha_fallecimiento, :causa_muerte, :deudo_id)";
            $stmtDifunto = $conn->prepare($queryDifunto);
            $stmtDifunto->bindParam(':dni', $data['difunto_dni']);
            $stmtDifunto->bindParam(':nombres', $data['difunto_nombres']);
            $stmtDifunto->bindParam(':apellidos', $data['difunto_apellidos']);
            $stmtDifunto->bindParam(':fecha_nacimiento', $data['fecha_nacimiento']);
            $stmtDifunto->bindParam(':fecha_fallecimiento', $data['fecha_fallecimiento']);
            $stmtDifunto->bindParam(':causa_muerte', $data['causa_muerte']);
            $stmtDifunto->bindParam(':deudo_id', $deudo_id);

            if (!$stmtDifunto->execute()) {
                throw new Exception("Error al registrar el Difunto.");
            }

            // Confirmar Transaccion
            $conn->commit();

            $_SESSION['success_msg'] = "Registro de deudo y difunto guardado existosamente.";
            header("Location: index.php?controller=vendedor&action=registro");
            exit();

        }
        catch (Exception $e) {
            // Revertir Transaccion si hay error
            $conn->rollBack();
            $_SESSION['error_msg'] = "Error en la transaccion: " . $e->getMessage();
            header("Location: index.php?controller=vendedor&action=registro");
            exit();
        }
    }

    public function guardarIntegral()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $database = new Conexion();
            $conn = $database->getConexion();

            try {
                $conn->beginTransaction();

                $dni_deudo = $_POST['dni_deudo'];
                $nombres_deudo = $_POST['nombres_deudo'];
                $apellidos_deudo = $_POST['apellidos_deudo'];
                $telefono_deudo = $_POST['telefono_deudo'];
                $direccion_deudo = $_POST['direccion_deudo'];

                $stmt_deudo = $conn->prepare("SELECT id FROM deudos WHERE dni = :dni LIMIT 1");
                $stmt_deudo->bindParam(':dni', $dni_deudo);
                $stmt_deudo->execute();

                if ($stmt_deudo->rowCount() > 0) {
                    $row = $stmt_deudo->fetch(PDO::FETCH_ASSOC);
                    $deudo_id = $row['id'];
                }
                else {
                    $stmt_ins_deudo = $conn->prepare("INSERT INTO deudos (dni, nombres, apellidos, telefono, direccion) VALUES (:dni, :nombres, :apellidos, :telefono, :direccion)");
                    $stmt_ins_deudo->bindParam(':dni', $dni_deudo);
                    $stmt_ins_deudo->bindParam(':nombres', $nombres_deudo);
                    $stmt_ins_deudo->bindParam(':apellidos', $apellidos_deudo);
                    $stmt_ins_deudo->bindParam(':telefono', $telefono_deudo);
                    $stmt_ins_deudo->bindParam(':direccion', $direccion_deudo);
                    $stmt_ins_deudo->execute();
                    $deudo_id = $conn->lastInsertId();
                }

                $dni_difunto = !empty($_POST['dni_difunto']) ? $_POST['dni_difunto'] : null;
                $nombres_difunto = $_POST['nombres_difunto'];
                $apellidos_difunto = $_POST['apellidos_difunto'];
                $fecha_fallecimiento = $_POST['fecha_fallecimiento'];
                $causa_muerte = $_POST['causa_muerte'];

                $stmt_difunto = $conn->prepare("INSERT INTO difuntos (dni, nombres, apellidos, fecha_fallecimiento, causa_muerte, deudo_id) VALUES (:dni, :nombres, :apellidos, :fecha, :causa, :deudo_id)");
                $stmt_difunto->bindParam(':dni', $dni_difunto);
                $stmt_difunto->bindParam(':nombres', $nombres_difunto);
                $stmt_difunto->bindParam(':apellidos', $apellidos_difunto);
                $stmt_difunto->bindParam(':fecha', $fecha_fallecimiento);
                $stmt_difunto->bindParam(':causa', $causa_muerte);
                $stmt_difunto->bindParam(':deudo_id', $deudo_id);
                $stmt_difunto->execute();

                $difunto_id = $conn->lastInsertId();

                $sede_id = $_POST['sede_id'];
                $tipo_servicio = $_POST['tipo_servicio'];
                $descripcion = $_POST['descripcion'] ?? '';
                $precio = $_POST['precio'];
                $vendedor_id = $_SESSION['id'];
                $estado = 'pendiente';
                $fecha_servicio = date('Y-m-d H:i:s');

                $stmt_servicio = $conn->prepare("INSERT INTO servicios (difunto_id, sede_id, vendedor_id, tipo_servicio, descripcion, precio, fecha_servicio, estado) VALUES (:dif_id, :sede_id, :vend_id, :tipo, :desc, :precio, :fecha, :estado)");
                $stmt_servicio->bindParam(':dif_id', $difunto_id);
                $stmt_servicio->bindParam(':sede_id', $sede_id);
                $stmt_servicio->bindParam(':vend_id', $vendedor_id);
                $stmt_servicio->bindParam(':tipo', $tipo_servicio);
                $stmt_servicio->bindParam(':desc', $descripcion);
                $stmt_servicio->bindParam(':precio', $precio);
                $stmt_servicio->bindParam(':fecha', $fecha_servicio);
                $stmt_servicio->bindParam(':estado', $estado);
                $stmt_servicio->execute();

                $conn->commit();
                header("Location: index.php?controller=dashboard&success=servicio_creado");
            }
            catch (Exception $e) {
                $conn->rollBack();
                header("Location: index.php?controller=vendedor&action=registro&error=" . urlencode($e->getMessage()));
            }
            exit();
        }
    }
}

// Enrutador de peticiones para el controlador
if (isset($_GET['action']) && $_GET['action'] == 'buscar_deudo' && isset($_GET['dni'])) {
    $controller = new DifuntoController();
    $controller->buscarDeudo($_GET['dni']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'registrar_difunto') {
    $controller = new DifuntoController();
    $controller->registrar($_POST);
}
?>
