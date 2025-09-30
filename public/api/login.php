<?php
require_once __DIR__ . '/db.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (!$username || !$password) {
	http_response_code(400);
	echo json_encode(["success" => false, "error" => "Datos incompletos"]);
	exit;
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
	$_SESSION['loggedIn'] = true;
	$_SESSION['username'] = $username;
	setcookie('loggedIn', 'true', time() + 3600, "/");
	echo json_encode(["success" => true]);
} else {
	http_response_code(401);
	echo json_encode(["success" => false, "error" => "Credenciales incorrectas"]);
}
