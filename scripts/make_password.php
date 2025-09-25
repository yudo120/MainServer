<?php
if ($argc < 2) {
    fwrite(STDERR, "Uso: php make_password.php <password>\n");
    exit(1);
}
$hash = password_hash($argv[1], PASSWORD_DEFAULT);
echo "Hash generado:\n$hash\n";
