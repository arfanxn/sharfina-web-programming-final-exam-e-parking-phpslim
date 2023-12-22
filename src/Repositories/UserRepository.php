<?php

namespace App\Repositories;

class UserRepository extends Repository
{
    /**
     * find finds a user by id
     *
     * @param string $id
     *
     */
    public function find(string $id)
    {
        var_dump($this->connection);
        die;

        $stmt = $this->connection->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(\PDO::FETCH_ASSOC) ?? null;
        var_dump($data);
    }

}
