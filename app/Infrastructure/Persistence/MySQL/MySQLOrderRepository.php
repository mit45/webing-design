<?php

namespace App\Infrastructure\Persistence\MySQL;

use App\Domain\Repositories\OrderRepositoryInterface;
use App\Domain\Entities\Order;
use App\Domain\Entities\OrderItem;
use PDO;

class MySQLOrderRepository implements OrderRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createOrder(Order $order, array $items): Order
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare('INSERT INTO orders (user_id, total_amount, currency, status, payment_reference, created_at) VALUES (:user_id, :total_amount, :currency, :status, :payment_reference, NOW())');
            $stmt->execute([
                ':user_id' => $order->getUserId(),
                ':total_amount' => $order->getTotalAmount(),
                ':currency' => $order->getCurrency(),
                ':status' => $order->getStatus(),
                ':payment_reference' => $order->getPaymentReference()
            ]);
            $orderId = (int)$this->pdo->lastInsertId();

            $stmtItem = $this->pdo->prepare('INSERT INTO order_items (order_id, product_id, unit_price, quantity) VALUES (:order_id, :product_id, :unit_price, :quantity)');
            foreach ($items as $it) {
                /** @var OrderItem $it */
                $stmtItem->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $it->getProductId(),
                    ':unit_price' => $it->getUnitPrice(),
                    ':quantity' => $it->getQuantity()
                ]);
            }

            $this->pdo->commit();
            return $this->findById($orderId);
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function findById(int $id): ?Order
    {
        $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;
        return new Order((int)$row['id'], (int)$row['user_id'], (float)$row['total_amount'], $row['currency'], $row['status'], $row['payment_reference']);
    }
}
