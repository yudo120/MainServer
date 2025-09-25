<?php
require __DIR__ . '/../inc/bootstrap.php';
ensure_logged_in();
require_csrf($_POST['csrf'] ?? '');
$path = $_POST['path'] ?? '';
$content = $_POST['content'] ?? '';
try {
    write_allowed_file($path, $content);
    echo "OK";
} catch (\Throwable $e) {
    http_response_code(400);
    echo "Error: " . $e->getMessage();
}
