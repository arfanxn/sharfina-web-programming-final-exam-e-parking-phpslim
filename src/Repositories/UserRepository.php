<?php

namespace App\Repositories;

use App\Models\User;
use DateTime;
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
        $isWithWhere = (!is_null($keyword) && $keyword != '');
        $stmt = $this->connection->prepare((new Stringy())
            ->append('SELECT * FROM users')
            ->append($isWithWhere ? ' WHERE name LIKE :keyword'  : '')
            ->append(' LIMIT :limit OFFSET :offset;')
            ->toString());
        if ($isWithWhere) {
            $stmt->bindParam(':keyword', '%' . $keyword . '%', \PDO::PARAM_STR);
        }
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: null;
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
        if (is_null($updatedAtStr) == false) {
            $stmt->bindParam(':updated_at', $updatedAtStr, \PDO::PARAM_STR);
        } else {
            $stmt->bindParam(':updated_at', null, \PDO::PARAM_NULL);
        }
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
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
        $stmt = $this->connection->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
