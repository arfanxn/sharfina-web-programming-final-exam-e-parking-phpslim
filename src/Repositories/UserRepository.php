<?php

namespace App\Repositories;

use App\Models\User;
use Stringy\Stringy;

class UserRepository extends Repository
{

    /**
     * paginate gets rows of users with pagination
     *
     * @param int $limit
     * @param int $offset
     * @param ?string $keyword
     * @return array array of users
     * @throws \PDOException
     */
    public function paginate(int $limit, int $offset, ?string $keyword = null): array
    {
        $isWithKeyword = (!is_null($keyword) && $keyword != '');
        $stmt = $this->connection->prepare((new Stringy())
            ->append('SELECT * FROM users')
            ->append($isWithKeyword ? ' WHERE name LIKE :keyword'  : '')
            ->append(' LIMIT :limit OFFSET :offset;')
            ->toString());
        if ($isWithKeyword) {
            $keyword = '%' . $keyword . '%';
            $stmt->bindParam(':keyword', $keyword, \PDO::PARAM_STR);
        }
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        $users = array();
        foreach ($rows as $row) {
            array_push($users, (new User())->hydrate($row));
        }

        return $users;
    }

    /**
     * find finds a user by id
     *
     * @param string $id
     * @return ?User
     * @throws \PDOException
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
     * @throws \PDOException
     */
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;

        return is_null($row) ? null : (new User())->hydrate($row);
    }

    /**
     * findLatest get the latest inserted row 
     *
     * @return ?User
     * @throws \PDOException
     */
    public function findLatest(): ?User
    {
        $stmt = $this->connection->prepare('SELECT * FROM users ORDER BY created_at DESC LIMIT 1');
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        return is_null($row) ? null : (new User())->hydrate($row);
    }

    /**
     * create creates a user by id
     *
     * @param User $user
     * @return int rows affected
     * @throws \PDOException
     */
    public function create(User $user): int
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO users (id, name, email, password, created_at, updated_at) VALUES (:id, :name, :email, :password, :created_at, :updated_at);'
        );
        $id = $user->getId();
        $name = $user->getName();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $createdAtStr = $user->getCreatedAt()->format('Y-m-d H:i:s');
        $updatedAtStr = $user->getUpdatedAt() ? $user->getUpdatedAt()->format('Y-m-d H:i:s') : null;
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, \PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $createdAtStr, \PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updatedAtStr, is_null($updatedAtStr) ? \PDO::PARAM_NULL : \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * update updates a user by id
     *
     * @param User $user
     * @return int rows affected
     * @throws \PDOException
     */
    public function update(User $user): int
    {
        $stmt = $this->connection->prepare(
            'UPDATE users SET name = :name, email = :email, password = :password, created_at = :created_at, updated_at = :updated_at WHERE id = :id'
        );
        $id = $user->getId();
        $name = $user->getName();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $createdAtStr = $user->getCreatedAt()->format('Y-m-d H:i:s');
        $updatedAtStr = $user->getUpdatedAt() ? $user->getUpdatedAt()->format('Y-m-d H:i:s') : null;
        $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, \PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $createdAtStr, \PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updatedAtStr, is_null($updatedAtStr) ? \PDO::PARAM_NULL : \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * delete deletes a user by id
     *
     * @param string $id
     * @return int rows affected
     * @throws \PDOException
     */
    public function delete(string $id): int
    {
        $this->connection->exec('SET foreign_key_checks = 0'); // disable foreign key checks during deletion
        $stmt = $this->connection->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $this->connection->exec('SET foreign_key_checks = 1'); // enabke foreign key checks after deletion
        return $stmt->rowCount();
    }
}
