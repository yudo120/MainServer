<?php
// Configuración de la base de datos SQLite para Apache
// Usar una ubicación accesible pero segura
$dbFile = __DIR__ . '/users.sqlite';

// Verificar que el directorio tenga permisos de escritura
if (!is_writable(__DIR__)) {
    // Si no tiene permisos, usar /tmp como fallback
    $dbFile = '/tmp/users.sqlite';
}

$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Crear tabla si no existe (ahora con columna role)
$pdo->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT "normal"
)');
