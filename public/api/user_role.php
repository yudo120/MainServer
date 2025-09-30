<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['username'])) {
    echo json_encode(["role" => null]);
    exit;
}
require_once __DIR__ . '/db.php';
$stmt = $pdo->prepare('SELECT role FROM users WHERE username = ?');
$stmt->execute([$_SESSION['username']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode(["role" => $row ? $row['role'] : null]);
