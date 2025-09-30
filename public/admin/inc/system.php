<?php
require_once __DIR__ . '/security.php';

function sys_temp(): ?string
{
    $candidates = [
        'vcgencmd measure_temp',
        "cat /sys/class/thermal/thermal_zone0/temp"
    ];
    foreach ($candidates as $c) {
        [$code, $out] = exec_command_safe($c, 2);
        if ($code === 0 && trim($out) !== '') {
            if (is_numeric(trim($out))) {
                return sprintf('%.1f°C', ((float) trim($out)) / 1000.0);
            }
            return trim($out);
        }
    }
    return null;
}

function sys_cpu(): string
{
    [$c, $o] = exec_command_safe("uptime", 2);
    return trim($o);
}

function sys_mem(): string
{
    [$c, $o] = exec_command_safe("free -m", 2);
    return trim($o);
}

function sys_disk(): string
{
    [$c, $o] = exec_command_safe("df -h", 2);
    return trim($o);
}
