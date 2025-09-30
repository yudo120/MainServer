<?php
require __DIR__ . '/../inc/bootstrap.php';
ensure_logged_in();
require_csrf($_POST['csrf'] ?? '');
$cmd = trim($_POST['cmd'] ?? '');
[$code, $out] = exec_command_safe($cmd, 8);
http_response_code($code === 0 ? 200 : 400);
header('Content-Type: text/plain; charset=utf-8');
echo $out;
