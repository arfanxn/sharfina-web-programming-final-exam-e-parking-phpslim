<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
    /**
     * find finds a user by id
     *
     * @param string $id
     * @return ?User
     */
    public function find(string $id): ?User
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;

        return is_null($data) ? null : (new User())->hydrate($data);
    }

    /**
     * findByEmail finds a user by email
     *
     * @param string $email
     * @return ?User
     */
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();

        $data = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;

        return is_null($data) ? null : (new User())->hydrate($data);
    }

}
