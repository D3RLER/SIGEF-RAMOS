<?php // session_start() handled by router ?>
<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
AuthMiddleware::redirectIfAuthenticated();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Funeraria SIGEF-RAMOS</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Roboto', sans-serif;
        }
        .login-container {
            min-height: 100vh;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08); /* Sombra elegante */
            overflow: hidden;
            background: #ffffff;
        }
        .login-header {
            background-color: #6D0032; /* Guinda institucional */
            color: white;
            padding: 40px 30px;
            text-align: center;
            border-bottom: 5px solid #520025; /* Contraste en la base del header */
        }
        .login-body {
            padding: 50px 40px;
        }
        .logo-svg {
            width: 70px;
            height: 70px;
            fill: white;
            margin-bottom: 15px;
        }
        .btn-custom {
            background-color: #6D0032;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            width: 100%;
            font-weight: 500;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .btn-custom:hover, .btn-custom:focus {
            background-color: #520025;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(109, 0, 50, 0.3);
        }
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
            border-color: #6D0032;
            box-shadow: 0 0 0 0.25rem rgba(109, 0, 50, 0.15);
        }
        .input-group-text {
            border-radius: 8px 0 0 8px;
            background-color: #f8f9fa;
        }
        .form-control {
            border-radius: 0 8px 8px 0;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center login-container">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="card login-card">
                <div class="login-header">
                    <!-- SVG Logo representation -->
                    <svg class="logo-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v4h4v2h-4v7h-2v-7H7v-2h4V7z"/>
                    </svg>
                    <h2 class="mb-0 fw-bold">SIGEF-RAMOS</h2>
                    <p class="mb-0 mt-2 text-white-50">Sistema de Gestion Funeraria</p>
                </div>
                <div class="login-body">
                    <?php if (isset($_SESSION['error_login'])): ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <?php
    echo $_SESSION['error_login'];
    unset($_SESSION['error_login']);
?>
                            </div>
                        </div>
                    <?php
endif; ?>
                    
                    <form action="../index.php?controller=auth&action=login" method="POST">
                        <div class="mb-4">
                            <label for="username" class="form-label text-secondary fw-semibold">Usuario</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" class="form-control" id="username" name="username" required placeholder="Ingrese su usuario">
                            </div>
                        </div>
                        <div class="mb-5">
                            <label for="password" class="form-label text-secondary fw-semibold">Contrasena</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Ingrese su contrasena">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye-fill" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-custom">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const togglePassword = document.getElementById("togglePassword");
            const passwordInput = document.getElementById("password");
            const toggleIcon = document.getElementById("toggleIcon");

            togglePassword.addEventListener("click", function () {
                // Alternar tipo de input
                const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                passwordInput.setAttribute("type", type);

                // Alternar icono y color
                if (type === "text") {
                    toggleIcon.classList.remove("bi-eye-fill");
                    toggleIcon.classList.add("bi-eye-slash-fill");
                    toggleIcon.style.color = "#6D0032"; // Guinda institucional al verse
                    togglePassword.style.borderColor = "#6D0032";
                } else {
                    toggleIcon.classList.remove("bi-eye-slash-fill");
                    toggleIcon.classList.add("bi-eye-fill");
                    toggleIcon.style.color = ""; // Color original de bootstrap
                    togglePassword.style.borderColor = "";
                }
            });
        });
    </script>
</body>
</html>
