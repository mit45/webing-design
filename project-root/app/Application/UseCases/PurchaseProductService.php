<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\OrderRepositoryInterface;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Infrastructure\Services\PaymentGatewayInterface;
use App\Domain\Entities\Order;
use App\Domain\Entities\OrderItem;

class PurchaseProductService
{
    private OrderRepositoryInterface $orders;
    private ProductRepositoryInterface $products;
    private PaymentGatewayInterface $payment;
    private GenerateLicenseService $licenseService;

    public function __construct(OrderRepositoryInterface $orders, ProductRepositoryInterface $products, PaymentGatewayInterface $payment, GenerateLicenseService $licenseService)
    {
        $this->orders = $orders;
        $this->products = $products;
        $this->payment = $payment;
        $this->licenseService = $licenseService;
    }

    /**
     * Bu metot bir kullanıcının bir ürünü satın almasını sağlar (basit tek ürün akışı).
     * Returns array with order and licenses on success.
     */
    public function handle(int $userId, int $productId): array
    {
        $product = $this->products->findById($productId);
        if (!$product) {
            throw new \InvalidArgumentException('Ürün bulunamadı');
        }
        if ($product->getStatus() !== 'published') {
            throw new \RuntimeException('Ürün satış için uygun değil');
        }

        $amount = $product->getPrice();
        $currency = $product->getCurrency();

        $paymentResult = $this->payment->charge($amount, $currency, ['user_id' => $userId, 'product_id' => $productId]);
        if (!$paymentResult['success']) {
            throw new \RuntimeException('Ödeme başarısız: ' . ($paymentResult['message'] ?? ''));
        }

        $order = new Order(null, $userId, $amount, $currency, 'completed', $paymentResult['reference']);
        $item = new OrderItem(null, 0, $productId, $amount, 1);
        $createdOrder = $this->orders->createOrder($order, [$item]);

        // Lisans üret
        $license = $this->licenseService->generate($userId, $productId);

        return ['order' => $createdOrder, 'license' => $license];
    }
}
