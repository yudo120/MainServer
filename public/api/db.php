<?php
// Configuración de la base de datos SQLite
$dbFile = '/tmp/users.sqlite';
// Alternativa: usar directorio con permisos completos
// $dbFile = __DIR__ . '/users.sqlite';

$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Crear tabla si no existe (ahora con columna role)
$pdo->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT "normal"
)');
