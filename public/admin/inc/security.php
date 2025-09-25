<?php
function is_command_allowed(string $cmd): bool
{
    return in_array($cmd, ALLOWED_COMMANDS, true);
}

function exec_command_safe(string $cmd, int $timeoutSec = 8): array
{
    if (!is_command_allowed($cmd)) {
        return [1, "Comando no permitido"];
    }

    $descriptors = [
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w']
    ];
    $proc = proc_open($cmd, $descriptors, $pipes);
    if (!is_resource($proc))
        return [1, "Error al ejecutar"];

    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    $exit = proc_close($proc);

    return [$exit, $stdout !== '' ? $stdout : $stderr];
}

function read_allowed_file(string $path): string
{
    if (!in_array($path, ALLOWED_FILES, true))
        throw new RuntimeException('Archivo no permitido');
    $data = @file_get_contents($path);
    if ($data === false)
        throw new RuntimeException('No se pudo leer');
    return $data;
}

function write_allowed_file(string $path, string $content): void
{
    if (!in_array($path, ALLOWED_FILES, true))
        throw new RuntimeException('Archivo no permitido');
    @copy($path, $path . '.bak.' . date('Ymd_His'));
    $ok = @file_put_contents($path, $content, LOCK_EX);
    if ($ok === false)
        throw new RuntimeException('No se pudo escribir');
}
