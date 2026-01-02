<?php

namespace App\Presentation\Middlewares;

use App\Infrastructure\Services\SessionManager;

class AuthMiddleware
{
    public static function requireAuth(): void
    {
        SessionManager::start(getenv('SESSION_NAME') ?: 'app_session', getenv('APP_ENV') === 'production');
        $user = SessionManager::get('user_id');
        if (!$user) {
            http_response_code(401);
            echo 'Yetkilendirme gerekiyor.';
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireAuth();
        $role = SessionManager::get('role');
        if ($role !== 'admin') {
            http_response_code(403);
            echo 'Admin yetkisi gerekli.';
            exit;
        }
    }
}
