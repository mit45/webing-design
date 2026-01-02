<?php

namespace App\Presentation\Controllers\Admin;

use App\Application\UseCases\CreateProductService;
use App\Infrastructure\Services\Csrf;
use App\Presentation\Middlewares\AuthMiddleware;

class ProductController
{
    private CreateProductService $createProduct;

    public function __construct(CreateProductService $createProduct)
    {
        $this->createProduct = $createProduct;
    }

    public function createForm(): void
    {
        AuthMiddleware::requireAdmin();
        echo '<h1>Yeni Ürün</h1>';
        echo '<form method="post" action="/admin/products">' . Csrf::tokenField() . '\n';
        echo '<input name="title" placeholder="Başlık"><input name="slug" placeholder="slug"><textarea name="description"></textarea><input name="price" placeholder="Fiyat"><select name="currency"><option>USD</option><option>EUR</option></select><button>Oluştur</button></form>';
    }

    public function store(): void
    {
        AuthMiddleware::requireAdmin();
        if (!Csrf::validate($_POST['_csrf_token'] ?? null)) {
            http_response_code(400);
            echo 'CSRF doğrulama başarısız.';
            return;
        }
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $currency = $_POST['currency'] ?? 'USD';
        if ($title === '' || $slug === '') {
            http_response_code(400);
            echo 'Başlık ve slug gereklidir.';
            return;
        }
        try {
            $product = $this->createProduct->handle($title, $slug, $description, $price, $currency, null, 'draft');
            echo 'Ürün oluşturuldu: ' . htmlspecialchars($product->getSlug(), ENT_QUOTES, 'UTF-8');
        } catch (\Exception $e) {
            http_response_code(500);
            echo 'Hata: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }
}
