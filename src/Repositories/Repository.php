<?php

namespace App\Repositories;

class Repository
{
    protected \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

}
