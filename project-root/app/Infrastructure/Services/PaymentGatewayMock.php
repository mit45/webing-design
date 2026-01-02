<?php

namespace App\Infrastructure\Services;

class PaymentGatewayMock implements PaymentGatewayInterface
{
    public function charge(float $amount, string $currency, array $meta = []): array
    {
        // Basit mock: küçük bir rastgele başarısızlık simülasyonu
        $ok = rand(1, 100) > 5; // %95 başarılı
        if ($ok) {
            return [
                'success' => true,
                'reference' => 'MOCK-' . bin2hex(random_bytes(8)),
                'message' => 'OK'
            ];
        }
        return [
            'success' => false,
            'reference' => null,
            'message' => 'Mock ödeme başarısız'
        ];
    }
}
