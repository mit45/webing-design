<?php

namespace App\Infrastructure\Services;

class SessionManager
{
    public static function start(string $name = 'app_session', bool $secure = false): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $params = session_get_cookie_params();
            session_set_cookie_params([
                'lifetime' => $params['lifetime'] ?? 0,
                'path' => $params['path'] ?? '/',
                'domain' => $params['domain'] ?? '',
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            session_name($name);
            session_start();
        }
    }

    public static function regenerate(bool $deleteOld = true): void
    {
        session_regenerate_id($deleteOld);
    }

    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
