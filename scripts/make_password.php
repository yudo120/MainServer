<?php
// make_password.php
if ($argc < 2) {
    echo "Uso: php make_password.php <contraseña>\n";
    exit(1);
}

$pass = $argv[1];
$hash = password_hash($pass, PASSWORD_DEFAULT);
echo "Contraseña: $pass\nHash: $hash\n";
