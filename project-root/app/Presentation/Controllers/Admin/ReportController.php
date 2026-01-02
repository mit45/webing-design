<?php

namespace App\Presentation\Controllers\Admin;

use App\Application\Services\AdminReportService;
use App\Presentation\Middlewares\AuthMiddleware;

class ReportController
{
    private AdminReportService $reports;

    public function __construct(AdminReportService $reports)
    {
        $this->reports = $reports;
    }

    public function dashboard(): void
    {
        AuthMiddleware::requireAdmin();
        $totalSales = $this->reports->totalSales();
        $totalUsers = $this->reports->totalUsers();
        $mostDownloaded = $this->reports->mostDownloadedProducts(10);

        echo '<h1>Admin Dashboard</h1>';
        echo '<p>Toplam satış: ' . htmlspecialchars((string)$totalSales, ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p>Toplam kullanıcı: ' . htmlspecialchars((string)$totalUsers, ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<h2>En çok indirilen ürünler</h2>';
        echo '<ul>';
        foreach ($mostDownloaded as $row) {
            echo '<li>Ürün ID: ' . htmlspecialchars((string)$row['product_id'], ENT_QUOTES, 'UTF-8') . ' — ' . htmlspecialchars((string)$row['downloads'], ENT_QUOTES, 'UTF-8') . ' indirme</li>';
        }
        echo '</ul>';
    }
}
