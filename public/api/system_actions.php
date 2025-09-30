<?php
session_start();
header('Content-Type: application/json');

// Verificar autenticación y permisos
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(["error" => "No autenticado"]);
    exit;
}

require_once __DIR__ . '/db.php';
$stmt = $pdo->prepare('SELECT role FROM users WHERE username = ?');
$stmt->execute([$_SESSION['username']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row || ($row['role'] !== 'owner' && $row['role'] !== 'admin')) {
    http_response_code(403);
    echo json_encode(["error" => "Sin permisos suficientes"]);
    exit;
}

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

// Función para cerrar todas las sesiones
function closeAllSessions() {
    // Limpiar todas las sesiones activas en la base de datos si tienes una tabla de sesiones
    // Por ahora, solo registrar la acción
    error_log("Cerrando todas las sesiones de usuario - " . date('Y-m-d H:i:s'));
}

switch ($action) {
    case 'restart_server':
        closeAllSessions();
        echo json_encode([
            "success" => true,
            "message" => "Reiniciando servidor PHP...",
            "action" => "restart_server"
        ]);
        
        // Registrar la acción
        error_log("SYSTEM ACTION: Restart server requested by " . $_SESSION['username'] . " at " . date('Y-m-d H:i:s'));
        
        // Usar ignore_user_abort para que el script continúe después de cerrar la conexión
        ignore_user_abort(true);
        
        // Enviar respuesta y cerrar conexión
        if (ob_get_level()) {
            ob_end_clean();
        }
        echo json_encode([
            "success" => true,
            "message" => "Servidor se reiniciará en 3 segundos"
        ]);
        
        // Forzar envío de la respuesta
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        
        // Esperar 3 segundos y reiniciar
        sleep(3);
        
        // En Windows, matar el proceso PHP actual
        if (PHP_OS_FAMILY === 'Windows') {
            exec('taskkill /F /IM php.exe && timeout 2 && cd "' . dirname(dirname(__DIR__)) . '" && start-php-server.bat', $output, $return_var);
        } else {
            // En Linux/Raspberry Pi
            exec('pkill -f "php -S localhost:8000"');
            sleep(1);
            exec('cd "' . dirname(dirname(__DIR__)) . '" && nohup ./start-php-server.sh > /dev/null 2>&1 &');
        }
        exit;
        
    case 'shutdown_server':
        closeAllSessions();
        error_log("SYSTEM ACTION: Shutdown server requested by " . $_SESSION['username'] . " at " . date('Y-m-d H:i:s'));
        
        echo json_encode([
            "success" => true,
            "message" => "Apagando servidor en 3 segundos..."
        ]);
        
        ignore_user_abort(true);
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        
        sleep(3);
        
        if (PHP_OS_FAMILY === 'Windows') {
            exec('taskkill /F /IM php.exe');
        } else {
            exec('pkill -f "php -S localhost:8000"');
        }
        exit;
        
    case 'restart_raspberry':
        if (PHP_OS_FAMILY === 'Windows') {
            echo json_encode(["error" => "Esta acción solo está disponible en Raspberry Pi"]);
            exit;
        }
        
        closeAllSessions();
        error_log("SYSTEM ACTION: Restart Raspberry Pi requested by " . $_SESSION['username'] . " at " . date('Y-m-d H:i:s'));
        
        echo json_encode([
            "success" => true,
            "message" => "Reiniciando Raspberry Pi en 3 segundos..."
        ]);
        
        ignore_user_abort(true);
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        
        sleep(3);
        exec('sudo reboot');
        exit;
        
    case 'shutdown_raspberry':
        if (PHP_OS_FAMILY === 'Windows') {
            echo json_encode(["error" => "Esta acción solo está disponible en Raspberry Pi"]);
            exit;
        }
        
        closeAllSessions();
        error_log("SYSTEM ACTION: Shutdown Raspberry Pi requested by " . $_SESSION['username'] . " at " . date('Y-m-d H:i:s'));
        
        echo json_encode([
            "success" => true,
            "message" => "Apagando Raspberry Pi en 3 segundos..."
        ]);
        
        ignore_user_abort(true);
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        
        sleep(3);
        exec('sudo shutdown -h now');
        exit;
        
    default:
        echo json_encode(["error" => "Acción no válida"]);
        exit;
}
?>