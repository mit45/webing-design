<?php

namespace App\Domain\Entities;

class DownloadLog
{
    private ?int $id;
    private ?int $userId;
    private int $productId;
    private ?string $ipAddress;
    private ?string $userAgent;
    private string $downloadedAt;

    public function __construct(?int $id, ?int $userId, int $productId, ?string $ipAddress, ?string $userAgent, ?string $downloadedAt = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->downloadedAt = $downloadedAt ?? date('Y-m-d H:i:s');
    }

    public function getId(): ?int { return $this->id; }
    public function getUserId(): ?int { return $this->userId; }
    public function getProductId(): int { return $this->productId; }
    public function getIpAddress(): ?string { return $this->ipAddress; }
    public function getUserAgent(): ?string { return $this->userAgent; }
    public function getDownloadedAt(): string { return $this->downloadedAt; }
}
