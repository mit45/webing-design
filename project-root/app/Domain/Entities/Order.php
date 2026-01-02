<?php

namespace App\Domain\Entities;

class Order
{
    private ?int $id;
    private int $userId;
    private float $totalAmount;
    private string $currency;
    private string $status;
    private ?string $paymentReference;

    public function __construct(?int $id, int $userId, float $totalAmount, string $currency = 'USD', string $status = 'pending', ?string $paymentReference = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->totalAmount = $totalAmount;
        $this->currency = $currency;
        $this->status = $status;
        $this->paymentReference = $paymentReference;
    }

    public function getId(): ?int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getTotalAmount(): float { return $this->totalAmount; }
    public function getCurrency(): string { return $this->currency; }
    public function getStatus(): string { return $this->status; }
    public function getPaymentReference(): ?string { return $this->paymentReference; }
}
