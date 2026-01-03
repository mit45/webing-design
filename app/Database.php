<?php
namespace App;

use PDO;

class Database
{
    public PDO $pdo;

    public function __construct(array $config)
    {
        $db = $config['db'];
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $db['host'], $db['port'], $db['name']);
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $this->pdo = new PDO($dsn, $db['user'], $db['pass'], $opt);
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}
