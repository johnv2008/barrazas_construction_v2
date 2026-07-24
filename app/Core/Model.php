<?php

declare(strict_types=1);

namespace App\Core;

use App\Services\DatabaseService;
use PDO;

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = DatabaseService::connection();
    }
}
