<?php

namespace App\Infrastructure\Persistence\MySQL;

use App\Domain\Repositories\LicenseRepositoryInterface;
use App\Domain\Entities\License;
use PDO;

class MySQLLicenseRepository implements LicenseRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(License $license): License
    {
        $stmt = $this->pdo->prepare('INSERT INTO licenses (user_id, product_id, license_key, status, expires_at, created_at) VALUES (:user_id, :product_id, :license_key, :status, :expires_at, NOW())');
        $stmt->execute([
            ':user_id' => $license->getUserId(),
            ':product_id' => $license->getProductId(),
            ':license_key' => $license->getLicenseKey(),
            ':status' => $license->getStatus(),
            ':expires_at' => $license->getExpiresAt()
        ]);
        $id = (int)$this->pdo->lastInsertId();
        return new License($id, $license->getUserId(), $license->getProductId(), $license->getLicenseKey(), $license->getStatus(), $license->getExpiresAt());
    }

    public function findByKey(string $key): ?License
    {
        $stmt = $this->pdo->prepare('SELECT * FROM licenses WHERE license_key = :key LIMIT 1');
        $stmt->execute([':key' => $key]);
        $row = $stmt->fetch();
        if (!$row) return null;
        return new License((int)$row['id'], (int)$row['user_id'], (int)$row['product_id'], $row['license_key'], $row['status'], $row['expires_at']);
    }
}
