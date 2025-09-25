<?php
function is_logged_in(): bool
{
    return isset($_SESSION['user']) && $_SESSION['user'] === ADMIN_USER;
}

function ensure_logged_in(): void
{
    if (!is_logged_in()) {
        header('Location: /admin');
        exit;
    }
    enforce_idle_timeout();
}

function enforce_idle_timeout(): void
{
    $now = time();
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = $now;
        return;
    }
    if (($now - $_SESSION['last_activity']) > SESSION_IDLE_MAX) {
        session_unset();
        session_destroy();
        header('Location: /admin?expired=1');
        exit;
    }
    $_SESSION['last_activity'] = $now;
}

function do_login(string $user, string $pass): bool
{
    if (hash_equals(ADMIN_USER, $user) && password_verify($pass, ADMIN_HASH)) {
        session_regenerate_id(true);
        $_SESSION['user'] = ADMIN_USER;
        $_SESSION['last_activity'] = time();
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
        return true;
    }
    return false;
}

function do_logout(): void
{
    session_unset();
    session_destroy();
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function require_csrf(string $token): void
{
    if (!hash_equals($_SESSION['csrf'] ?? '', $token)) {
        http_response_code(403);
        exit('CSRF token inválido');
    }
}
