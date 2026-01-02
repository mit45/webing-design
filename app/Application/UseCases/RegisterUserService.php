<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\ValueObjects\Email;
use App\Domain\Entities\User;

class RegisterUserService
{
    private UserRepositoryInterface $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function handle(string $name, string $email, string $password): User
    {
        $emailVo = new Email($email);
        if ($this->users->findByEmail($emailVo)) {
            throw new \InvalidArgumentException('E-posta zaten kayıtlı');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $user = new User(null, $name, $emailVo, $passwordHash);
        return $this->users->save($user);
    }
}
