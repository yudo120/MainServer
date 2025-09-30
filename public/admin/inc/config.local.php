<?php
// Usuario admin
const ADMIN_USER = 'admin';

// Hash generado con scripts/make_password.php
const ADMIN_HASH = '$2y$10$4UmIfmD7Q/sL89BV/aw8Y.awaibzJ7RG9xyZXyhv5o2h4ufU3WgRK';

// Expiración de sesión
const SESSION_IDLE_MAX = 600; // 10 min

// Lista blanca de comandos para consola
const ALLOWED_COMMANDS = [
    'uptime',
    'whoami',
    'uname -a',
    'df -h',
    'free -m',
    'vcgencmd measure_temp',
    'tail -n 100 /var/log/syslog'
];

// Archivos permitidos para editar
const ALLOWED_FILES = [
    '/etc/hostname'
];
