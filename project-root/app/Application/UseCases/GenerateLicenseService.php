<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\LicenseRepositoryInterface;
use App\Domain\Entities\License;

class GenerateLicenseService
{
    private LicenseRepositoryInterface $licenses;

    public function __construct(LicenseRepositoryInterface $licenses)
    {
        $this->licenses = $licenses;
    }

    public function generate(int $userId, int $productId, ?\DateTimeInterface $expires = null): License
    {
        $key = $this->generateKey($userId, $productId);
        $expiresAt = $expires ? $expires->format('Y-m-d H:i:s') : null;
        $license = new License(null, $userId, $productId, $key, 'active', $expiresAt);
        return $this->licenses->save($license);
    }

    private function generateKey(int $userId, int $productId): string
    {
        return strtoupper(bin2hex(random_bytes(8))) . '-' . $userId . '-' . $productId;
    }
}
