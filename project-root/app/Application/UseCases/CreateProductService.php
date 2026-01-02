<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProductRepositoryInterface;
use App\Domain\Entities\Product;

class CreateProductService
{
    private ProductRepositoryInterface $products;

    public function __construct(ProductRepositoryInterface $products)
    {
        $this->products = $products;
    }

    public function handle(string $title, string $slug, ?string $description, float $price, string $currency = 'USD', ?int $categoryId = null, string $status = 'draft'): Product
    {
        $product = new Product(null, $title, $slug, $description, $price, $currency, $categoryId, $status);
        return $this->products->save($product);
    }
}
