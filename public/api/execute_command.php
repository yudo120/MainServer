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
$command = trim($input['command'] ?? '');

if (empty($command)) {
    echo json_encode(["error" => "Comando vacío"]);
    exit;
}

// Lista de comandos prohibidos por seguridad
$forbidden_commands = [
    'rm -rf /',
    'dd if=',
    'mkfs',
    'fdisk',
    'passwd',
    'userdel',
    'deluser',
    '> /dev/',
    'chmod 777 /',
    'chown root',
];

$command_lower = strtolower($command);
foreach ($forbidden_commands as $forbidden) {
    if (strpos($command_lower, strtolower($forbidden)) !== false) {
        echo json_encode(["error" => "Comando prohibido por seguridad"]);
        exit;
    }
}

// Ejecutar comando con límite de tiempo
$descriptorspec = array(
    0 => array("pipe", "r"),
    1 => array("pipe", "w"),
    2 => array("pipe", "w")
);

$process = proc_open($command, $descriptorspec, $pipes);

if (is_resource($process)) {
    // Cerrar stdin
    fclose($pipes[0]);
    
    // Leer stdout y stderr con timeout
    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);
    
    $stdout = '';
    $stderr = '';
    $timeout = 30; // 30 segundos timeout
    $start_time = time();
    
    while (time() - $start_time < $timeout) {
        $stdout .= fread($pipes[1], 4096);
        $stderr .= fread($pipes[2], 4096);
        
        $status = proc_get_status($process);
        if (!$status['running']) {
            break;
        }
        usleep(100000); // 0.1 segundos
    }
    
    $stdout .= stream_get_contents($pipes[1]);
    $stderr .= stream_get_contents($pipes[2]);
    
    fclose($pipes[1]);
    fclose($pipes[2]);
    
    $exit_code = proc_close($process);
    
    echo json_encode([
        "success" => true,
        "command" => $command,
        "stdout" => $stdout,
        "stderr" => $stderr,
        "exit_code" => $exit_code,
        "timestamp" => date('Y-m-d H:i:s')
    ]);
} else {
    echo json_encode(["error" => "No se pudo ejecutar el comando"]);
}
?>