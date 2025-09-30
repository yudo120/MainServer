<?php
// Script para crear usuario admin y owner si no existen
require_once __DIR__ . '/db.php';

function createUser($username, $password, $role) {
    global $pdo;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
        $stmt->execute([$username, $hash, $role]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}


// Eliminar owner si existe
$pdo->prepare('DELETE FROM users WHERE username = ?')->execute(['juan']);

$ownerUser = 'juan';
$ownerPass = 'jmdc1511';
$ownerCreated = createUser($ownerUser, $ownerPass, 'owner');

header('Content-Type: text/plain');
echo "Usuario owner generado:\n";
if ($ownerCreated) {
    echo "Owner: $ownerUser / $ownerPass\n";
} else {
    echo "No se pudo crear el owner\n";
}
