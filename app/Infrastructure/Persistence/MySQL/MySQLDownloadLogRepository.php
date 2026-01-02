<?php

namespace App\Infrastructure\Persistence\MySQL;

use App\Domain\Repositories\DownloadLogRepositoryInterface;
use App\Domain\Entities\DownloadLog;
use PDO;

class MySQLDownloadLogRepository implements DownloadLogRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(DownloadLog $log): DownloadLog
    {
        $stmt = $this->pdo->prepare('INSERT INTO downloads_log (user_id, product_id, ip_address, user_agent, downloaded_at) VALUES (:user_id, :product_id, :ip_address, :user_agent, :downloaded_at)');
        $stmt->execute([
            ':user_id' => $log->getUserId(),
            ':product_id' => $log->getProductId(),
            ':ip_address' => $log->getIpAddress(),
            ':user_agent' => $log->getUserAgent(),
            ':downloaded_at' => $log->getDownloadedAt(),
        ]);
        $id = (int)$this->pdo->lastInsertId();
        return new DownloadLog($id, $log->getUserId(), $log->getProductId(), $log->getIpAddress(), $log->getUserAgent(), $log->getDownloadedAt());
    }

    public function mostDownloadedProducts(int $limit = 10): array
    {
        $stmt = $this->pdo->prepare('SELECT product_id, COUNT(*) as downloads FROM downloads_log GROUP BY product_id ORDER BY downloads DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
