<?php
require __DIR__ . '/../inc/bootstrap.php';
require_csrf($_POST['csrf'] ?? '');
do_logout();
header('Location: /admin');
