<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "error" => "Solo se permite POST"]);
    exit;
}

require_once __DIR__ . '/db.php';
file_put_contents(__DIR__ . '/logs/register.log', date('c') . " - POST recibido\n", FILE_APPEND);

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$role = 'normal';
if (isset($data['role']) && in_array($data['role'], ['admin', 'owner'])) {
    $role = $data['role'];
}
try {
    $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
    $stmt->execute([$username, $hash, $role]);
    echo json_encode(["success" => true]);
    file_put_contents(__DIR__ . '/logs/register.log', date('c') . " - Usuario registrado: $username ($role)\n", FILE_APPEND);
} catch (PDOException $e) {
    http_response_code(409);
    echo json_encode(["success" => false, "error" => "Usuario ya existe"]);
    file_put_contents(__DIR__ . '/logs/register.log', date('c') . " - Usuario ya existe: $username\n", FILE_APPEND);
}
