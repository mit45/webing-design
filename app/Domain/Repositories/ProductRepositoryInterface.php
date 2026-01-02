<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;
    public function findBySlug(string $slug): ?Product;
    public function save(Product $product): Product;
    public function searchByTitleOrDescription(string $q, int $limit = 20): array;
}
