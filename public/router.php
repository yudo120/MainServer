<?php
// router.php para el servidor embebido de PHP
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Rutas amigables a archivos HTML
$routes = [
    '/servicios' => '/pages/servicios.html',
    '/tools' => '/pages/tools.html',
    '/docs' => '/pages/docs.html',
    '/control-panel' => '/pages/control-panel.html',
    '/' => '/index.html',
];


if (isset($routes[$uri])) {
    include __DIR__ . $routes[$uri];
    exit;
}

// Si el archivo existe, servirlo normalmente
$file = __DIR__ . $uri;
if (is_file($file)) {
    return false;
}

// Si no existe, mostrar 404
http_response_code(404);
echo '404 Not Found';
