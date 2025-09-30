<?php
file_put_contents(__DIR__ . '/logs/test.log', date('c') . "\n", FILE_APPEND);
echo 'Usuario Apache/PHP: ' . exec('whoami');
