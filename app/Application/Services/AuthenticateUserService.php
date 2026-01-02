<?php

namespace App\Application\Services;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Services\SessionManager;
use App\Domain\ValueObjects\Email;

class AuthenticateUserService
{
    private UserRepositoryInterface $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function authenticate(string $email, string $password): bool
    {
        $emailVo = new Email($email);
        $user = $this->users->findByEmail($emailVo);
        if (!$user) return false;

        if (!password_verify($password, $user->getPasswordHash())) {
            return false;
        }

        SessionManager::start(getenv('SESSION_NAME') ?: 'app_session', getenv('APP_ENV') === 'production');
        SessionManager::regenerate();
        SessionManager::set('user_id', $user->getId());
        // basit: role id -> role name dönüşümü prod ortamda repository ile çekilmeli
        SessionManager::set('role', $user->getRoleId() === 1 ? 'admin' : 'customer');
        return true;
    }

    public function logout(): void
    {
        SessionManager::destroy();
    }
}
