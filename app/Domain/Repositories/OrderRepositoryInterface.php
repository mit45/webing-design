<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Order;
use App\Domain\Entities\OrderItem;

interface OrderRepositoryInterface
{
    public function createOrder(Order $order, array $items): Order;
    public function findById(int $id): ?Order;
}
