<?php

namespace App\Domain\Entities;

class License
{
    private ?int $id;
    private int $userId;
    private int $productId;
    private string $licenseKey;
    private string $status;
    private ?string $expiresAt;

    public function __construct(?int $id, int $userId, int $productId, string $licenseKey, string $status = 'active', ?string $expiresAt = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->licenseKey = $licenseKey;
        $this->status = $status;
        $this->expiresAt = $expiresAt;
    }

    public function getId(): ?int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getProductId(): int { return $this->productId; }
    public function getLicenseKey(): string { return $this->licenseKey; }
    public function getStatus(): string { return $this->status; }
    public function getExpiresAt(): ?string { return $this->expiresAt; }
}
