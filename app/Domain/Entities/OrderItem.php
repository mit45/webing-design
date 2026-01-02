<?php

namespace App\Domain\Entities;

class OrderItem
{
    private ?int $id;
    private int $orderId;
    private int $productId;
    private float $unitPrice;
    private int $quantity;

    public function __construct(?int $id, int $orderId, int $productId, float $unitPrice, int $quantity = 1)
    {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
    }

    public function getId(): ?int { return $this->id; }
    public function getOrderId(): int { return $this->orderId; }
    public function getProductId(): int { return $this->productId; }
    public function getUnitPrice(): float { return $this->unitPrice; }
    public function getQuantity(): int { return $this->quantity; }
}
