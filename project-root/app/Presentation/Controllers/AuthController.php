<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\RegisterUserService;
use App\Application\Services\AuthenticateUserService;
use App\Infrastructure\Services\SessionManager;
use App\Infrastructure\Services\Csrf;

class AuthController
{
    private RegisterUserService $register;
    private AuthenticateUserService $auth;

    public function __construct(RegisterUserService $register, AuthenticateUserService $auth)
    {
        $this->register = $register;
        $this->auth = $auth;
    }

    public function showRegister(): void
    {
        SessionManager::start(getenv('SESSION_NAME') ?: 'app_session', getenv('APP_ENV') === 'production');
        echo '<form method="post" action="/register">' . Csrf::tokenField() . '\n';
        echo '<input name="name"><input name="email"><input name="password" type="password"><button>Kayıt</button></form>';
    }

    public function register(): void
    {
        SessionManager::start(getenv('SESSION_NAME') ?: 'app_session', getenv('APP_ENV') === 'production');
        if (!Csrf::validate($_POST['_csrf_token'] ?? null)) {
            http_response_code(400);
            echo 'CSRF doğrulama başarısız.';
            return;
        }
        try {
            $user = $this->register->handle($_POST['name'], $_POST['email'], $_POST['password']);
            echo 'Kayıt başarılı: ' . htmlspecialchars($user->getEmail()->getValue(), ENT_QUOTES, 'UTF-8');
        } catch (\Exception $e) {
            http_response_code(400);
            echo 'Kayıt hatası: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }

    public function showLogin(): void
    {
        SessionManager::start(getenv('SESSION_NAME') ?: 'app_session', getenv('APP_ENV') === 'production');
        echo '<form method="post" action="/login">' . Csrf::tokenField() . '\n';
        echo '<input name="email"><input name="password" type="password"><button>Giriş</button></form>';
    }

    public function login(): void
    {
        SessionManager::start(getenv('SESSION_NAME') ?: 'app_session', getenv('APP_ENV') === 'production');
        if (!Csrf::validate($_POST['_csrf_token'] ?? null)) {
            http_response_code(400);
            echo 'CSRF doğrulama başarısız.';
            return;
        }
        $ok = $this->auth->authenticate($_POST['email'], $_POST['password']);
        if ($ok) {
            echo 'Giriş başarılı.';
        } else {
            http_response_code(401);
            echo 'Giriş başarısız.';
        }
    }

    public function logout(): void
    {
        $this->auth->logout();
        echo 'Çıkış yapıldı.';
    }
}
