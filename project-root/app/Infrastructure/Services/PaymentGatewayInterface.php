<?php

namespace App\Infrastructure\Services;

interface PaymentGatewayInterface
{
    /**
     * Process a payment and return an array with keys: success(bool), reference(string|null), message(string|null)
     * Implementations should be idempotent if possible.
     */
    public function charge(float $amount, string $currency, array $meta = []): array;
}
