<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\PurchaseProductService;
use App\Presentation\Middlewares\AuthMiddleware;
use App\Infrastructure\Services\SessionManager;

class PurchaseController
{
    private PurchaseProductService $purchase;

    public function __construct(PurchaseProductService $purchase)
    {
        $this->purchase = $purchase;
    }

    public function buy(): void
    {
        AuthMiddleware::requireAuth();
        SessionManager::start(getenv('SESSION_NAME') ?: 'app_session', getenv('APP_ENV') === 'production');
        $userId = (int)SessionManager::get('user_id');
        $productId = (int)($_POST['product_id'] ?? 0);
        if ($productId <= 0) {
            http_response_code(400);
            echo 'Geçersiz ürün';
            return;
        }
        try {
            $res = $this->purchase->handle($userId, $productId);
            echo 'Satın alma başarılı. Lisans: ' . htmlspecialchars($res['license']->getLicenseKey(), ENT_QUOTES, 'UTF-8');
        } catch (\Exception $e) {
            http_response_code(400);
            echo 'Satın alma hatası: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }
}
