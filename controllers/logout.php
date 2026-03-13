<?php
// Destruir todas las variables de sesion
session_unset();
// Destruir la sesion
session_destroy();
// Redirigir al login
header("Location: ../views/login.php");
exit();
?>
