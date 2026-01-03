<?php
namespace App\Models;

use App\Database;

class Model
{
    protected Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
}
