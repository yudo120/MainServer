<?php
require __DIR__ . '/../inc/bootstrap.php';
require_csrf($_POST['csrf'] ?? '');

$user = trim($_POST['user'] ?? '');
$pass = $_POST['pass'] ?? '';

if (do_login($user, $pass)) {
    header('Location: /admin');
    exit;
}
header('Location: /admin?err=1');
