<?php

namespace App\Infrastructure\Persistence\MySQL;

use App\Domain\Repositories\ProductRepositoryInterface;
use App\Domain\Entities\Product;
use PDO;

class MySQLProductRepository implements ProductRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?Product
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;
        return $this->hydrate($row);
    }

    public function findBySlug(string $slug): ?Product
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE slug = :slug LIMIT 1');
        $stmt->execute([':slug' => $slug]);
        $row = $stmt->fetch();
        if (!$row) return null;
        return $this->hydrate($row);
    }

    public function save(Product $product): Product
    {
        if ($product->getId()) {
            $stmt = $this->pdo->prepare('UPDATE products SET title=:title, slug=:slug, description=:description, price=:price, currency=:currency, category_id=:category_id, status=:status, updated_at=NOW() WHERE id=:id');
            $stmt->execute([
                ':title' => $product->getTitle(),
                ':slug' => $product->getSlug(),
                ':description' => $product->getDescription(),
                ':price' => $product->getPrice(),
                ':currency' => $product->getCurrency(),
                ':category_id' => $product->getCategoryId(),
                ':status' => $product->getStatus(),
                ':id' => $product->getId(),
            ]);
            return $product;
        }

        $stmt = $this->pdo->prepare('INSERT INTO products (title, slug, description, price, currency, category_id, status, created_at) VALUES (:title, :slug, :description, :price, :currency, :category_id, :status, NOW())');
        $stmt->execute([
            ':title' => $product->getTitle(),
            ':slug' => $product->getSlug(),
            ':description' => $product->getDescription(),
            ':price' => $product->getPrice(),
            ':currency' => $product->getCurrency(),
            ':category_id' => $product->getCategoryId(),
            ':status' => $product->getStatus(),
        ]);
        $id = (int)$this->pdo->lastInsertId();
        return $this->findById($id);
    }

    public function searchByTitleOrDescription(string $q, int $limit = 20): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE (title LIKE :q OR description LIKE :q) AND status = "published" LIMIT :limit');
        $like = '%' . $q . '%';
        $stmt->bindValue(':q', $like, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->hydrate($row);
        }
        return $results;
    }

    private function hydrate(array $row): Product
    {
        return new Product((int)$row['id'], $row['title'], $row['slug'], $row['description'] ?? null, (float)$row['price'], $row['currency'], isset($row['category_id']) ? (int)$row['category_id'] : null, $row['status']);
    }
}
