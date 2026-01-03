<?php
namespace App\Models;

use PDO;

class Product extends Model
{
    public function all()
    {
        $stmt = $this->db->pdo()->query('SELECT * FROM products ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id)
    {
        $stmt = $this->db->pdo()->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $stmt = $this->db->pdo()->prepare('INSERT INTO products (category_id,title,slug,short_description,description,price,currency,status,created_at) VALUES (?,?,?,?,?,?,?,?,NOW())');
        return $stmt->execute([
            $data['category_id'] ?? null,
            $data['title'],
            $data['slug'],
            $data['short_description'] ?? null,
            $data['description'] ?? null,
            $data['price'] ?? 0,
            $data['currency'] ?? 'USD',
            $data['status'] ?? 'draft'
        ]);
    }
}
