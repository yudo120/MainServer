<?php
require_once __DIR__ . '/db.php';
header('Content-Type: text/plain');
$stmt = $pdo->query('PRAGMA table_info(users)');
echo "Estructura de la tabla users:\n";
foreach ($stmt as $col) {
    echo $col['name'] . ' (' . $col['type'] . ")\n";
}
echo "\nUsuarios registrados:\n";
$stmt = $pdo->query('SELECT id, username, role FROM users');
foreach ($stmt as $row) {
    echo "ID: {$row['id']} | Usuario: {$row['username']} | Rol: {$row['role']}\n";
}
