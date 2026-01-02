<?php

namespace App\Infrastructure\Persistence\MySQL;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\Email;
use PDO;

class MySQLUserRepository implements UserRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;
        return $this->hydrateUser($row);
    }

    public function findByEmail(Email $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => (string)$email]);
        $row = $stmt->fetch();
        if (!$row) return null;
        return $this->hydrateUser($row);
    }

    public function save(User $user): User
    {
        if ($user->getId()) {
            // update (basit örnek)
            $stmt = $this->pdo->prepare('UPDATE users SET name = :name, email = :email, password = :password, role_id = :role_id, status = :status, updated_at = NOW() WHERE id = :id');
            $stmt->execute([
                ':name' => $user->getName(),
                ':email' => (string)$user->getEmail(),
                ':password' => $user->getPasswordHash(),
                ':role_id' => $user->getRoleId(),
                ':status' => $user->getStatus(),
                ':id' => $user->getId(),
            ]);
            return $user;
        }

        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password, role_id, status, created_at) VALUES (:name, :email, :password, :role_id, :status, NOW())');
        $stmt->execute([
            ':name' => $user->getName(),
            ':email' => (string)$user->getEmail(),
            ':password' => $user->getPasswordHash(),
            ':role_id' => $user->getRoleId(),
            ':status' => $user->getStatus(),
        ]);
        $id = (int)$this->pdo->lastInsertId();
        // yeni ID ile geri döndürmek için basit bir yeniden yükleme
        return $this->findById($id);
    }

    private function hydrateUser(array $row): User
    {
        $email = new Email($row['email']);
        return new User((int)$row['id'], $row['name'], $email, $row['password'], (int)$row['role_id'], $row['status']);
    }
}
