<?php
require __DIR__ . '/../inc/bootstrap.php';
require_csrf($_POST['csrf'] ?? '');

$user = trim($_POST['user'] ?? '');
$pass = $_POST['pass'] ?? '';

if (do_login($user, $pass)) {
    header('Location: /admin');
    exit;
}

// Guardamos flag de error en sesión
$_SESSION['login_error'] = '❌ Usuario o contraseña incorrectos';
header('Location: /admin');
exit;
