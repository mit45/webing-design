<?php
namespace App\Models;

use PDO;

class User extends Model
{
    public function findByEmail(string $email)
    {
        $stmt = $this->db->pdo()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $stmt = $this->db->pdo()->prepare('INSERT INTO users (role_id,name,email,password,created_at) VALUES (?,?,?,?,NOW())');
        return $stmt->execute([
            $data['role_id'] ?? 2,
            $data['name'],
            $data['email'],
            $data['password']
        ]);
    }
}
