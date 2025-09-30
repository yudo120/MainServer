<?php
// Simulación de logout simple
session_start();

// Limpiar todas las variables de sesión
unset($_SESSION['loggedIn']);
unset($_SESSION['username']);
session_destroy();

// Limpiar cookie
setcookie('loggedIn', '', time() - 3600, "/");

http_response_code(200);
echo json_encode(["success" => true]);
