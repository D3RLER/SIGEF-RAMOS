<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $usuarioModel = new Usuario();
            $user = $usuarioModel->login($username, $password);

            if ($user) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre'];

                $rol_real = $user['rol'];
                // Promover a Admin si es Gerente global sin Sede asignada
                if ($rol_real === 'Gerente' && empty($user['sede_id'])) {
                    $rol_real = 'Admin';
                }

                $_SESSION['rol'] = $rol_real;
                $_SESSION['sede'] = $user['sede_nombre'] ?? 'Matriz Global';
                $_SESSION['sede_id'] = $user['sede_id'];

                // Redirige al index que maneja el enrutamiento segun rol
                if ($rol_real === 'Admin') {
                    header("Location: /SIGEF-RAMOS/index.php?controller=hub");
                }
                elseif ($rol_real === 'Gerente') {
                    header("Location: /SIGEF-RAMOS/index.php?controller=dashboard");
                }
                elseif ($rol_real === 'Vendedor') {
                    header("Location: /SIGEF-RAMOS/index.php?controller=dashboard"); // Panel de Ventas
                }
                elseif ($rol_real === 'Operario') {
                    header("Location: /SIGEF-RAMOS/index.php?controller=operario"); // Tablero Logistica
                }
                else {
                    header("Location: /SIGEF-RAMOS/index.php");
                }
                exit();
            }
            else {
                $_SESSION['error_login'] = "Usuario o contrasena incorrectos.";
                header("Location: /SIGEF-RAMOS/views/login.php");
                exit();
            }
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: /SIGEF-RAMOS/views/login.php");
        exit();
    }

    // --- METODOS PARA CLIENTES ---

    public function procesar_registro_cliente()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../models/Cliente.php';
            $clienteModel = new Cliente();

            $p_nombre = trim($_POST['p_nombre'] ?? '');
            $s_nombre = trim($_POST['s_nombre'] ?? '');
            $a_paterno = trim($_POST['a_paterno'] ?? '');
            $a_materno = trim($_POST['a_materno'] ?? '');
            $dni = trim($_POST['dni'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($p_nombre) || empty($a_paterno) || empty($a_materno) || empty($dni) || empty($email) || empty($password)) {
                $_SESSION['error_registro'] = "Por favor, complete todos los campos obligatorios.";
                $_SESSION['show_register_modal'] = true;
                header("Location: /SIGEF-RAMOS/index.php?controller=public");
                exit();
            }

            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            if ($clienteModel->registrar($p_nombre, $s_nombre, $a_paterno, $a_materno, $dni, $email, $hashed_password)) {
                // Auto login
                $user = $clienteModel->login($email, $password);
                if ($user) {
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['nombre'] = $user['nombre_completo'];
                    $_SESSION['dni'] = $user['dni']; // Importante para enlazar con servicios
                    $_SESSION['rol'] = 'Cliente';
                    header("Location: /SIGEF-RAMOS/index.php");
                    exit();
                }
            }
            else {
                $_SESSION['error_registro'] = "El DNI o Correo Electronico ya estan registrados.";
                $_SESSION['show_register_modal'] = true;
                header("Location: /SIGEF-RAMOS/index.php?controller=public");
                exit();
            }
        }
    }

    public function procesar_login_cliente()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../models/Cliente.php';
            $clienteModel = new Cliente();

            $identificador = trim($_POST['identificador'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $clienteModel->login($identificador, $password);

            if ($user) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre_completo'];
                $_SESSION['dni'] = $user['dni'];
                $_SESSION['rol'] = 'Cliente';
                header("Location: /SIGEF-RAMOS/index.php");
                exit();
            }
            else {
                $_SESSION['error_login'] = "DNI o Contrasena incorrectos.";
                $_SESSION['show_login_modal'] = true;
                header("Location: /SIGEF-RAMOS/index.php?controller=public");
                exit();
            }
        }
    }
}
?>
