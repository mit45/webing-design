<?php

namespace App\Application\Services;

use App\Domain\Repositories\OrderRepositoryInterface;
use App\Domain\Repositories\DownloadLogRepositoryInterface;
use PDO;

class AdminReportService
{
    private PDO $pdo;
    private OrderRepositoryInterface $orders;
    private DownloadLogRepositoryInterface $downloads;

    public function __construct(PDO $pdo, OrderRepositoryInterface $orders, DownloadLogRepositoryInterface $downloads)
    {
        $this->pdo = $pdo;
        $this->orders = $orders;
        $this->downloads = $downloads;
    }

    public function totalSales\(string $from = null, string $to = null): float
    {
        $sql = 'SELECT SUM(total_amount) as total FROM orders WHERE 1=1';
        $params = [];
        if ($from) {
            $sql .= ' AND created_at >= :from';
            $params[':from'] = $from;
        }
        if ($to) {
            $sql .= ' AND created_at <= :to';
            $params[':to'] = $to;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)($row['total'] ?? 0.0);
    }

    public function mostDownloadedProducts(int $limit = 10): array
    {
        return $this->downloads->mostDownloadedProducts($limit);
    }

    public function totalUsers(): int
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) as cnt FROM users');
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['cnt'] ?? 0);
    }
}
