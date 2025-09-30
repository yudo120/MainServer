<?php
require __DIR__ . '/inc/bootstrap.php';
ensure_logged_in();

$path = $_GET['path'] ?? '';
try {
    $content = read_allowed_file($path);
    header('Content-Type: text/plain; charset=utf-8');
    echo $content;
} catch (\Throwable $e) {
    http_response_code(400);
    echo "Error: " . $e->getMessage();
}
