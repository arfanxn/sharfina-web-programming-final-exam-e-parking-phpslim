<?php

namespace App\Repositories;

use App\Models\VehicleType;
use Stringy\Stringy;

class VehicleTypeRepository extends Repository
{

    /**
     * all gets rows of vehicle_types
     *
     * @return array array of vehicle_types
     * @throws \PDOException
     */
    public function all(): array
    {
        $stmt = $this->connection->prepare((new Stringy())
            ->append('SELECT * FROM vehicle_types')
            ->append(' ORDER BY vehicle_types.type ASC')
            ->toString());
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        $vehicleTypes = array();
        foreach ($rows as $row) {
            array_push($vehicleTypes, (new VehicleType())->hydrate($row));
        }

        return $vehicleTypes;
    }

    /**
     * find finds a user by id
     *
     * @param int $id
     * @return ?VehicleType
     * @throws \PDOException
     */
    public function find(int $id): ?VehicleType
    {
        $stmt = $this->connection->prepare('SELECT * FROM vehicle_types WHERE id = :id');
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;

        return is_null($data) ? null : (new VehicleType())->hydrate($data);
    }

    /**
     * findLatest get the latest inserted row 
     *
     * @return ?VehicleType
     * @throws \PDOException
     */
    public function findLatest(): ?VehicleType
    {
        $stmt = $this->connection->prepare('SELECT * FROM vehicle_types ORDER BY created_at DESC LIMIT 1');
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        return is_null($row) ? null : (new VehicleType())->hydrate($row);
    }

    /**
     * create 
     *
     * @param VehicleType $vehicleType
     * @return int rows affected
     * @throws \PDOException
     */
    public function create(VehicleType $vehicleType): int
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO vehicle_types (id, type) VALUES (:id, :type);'
        );
        $this->bindStatement($stmt, $vehicleType);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * update updates a user by id
     *
     * @param VehicleType $vehicleType
     * @return int rows affected
     * @throws \PDOException
     */
    public function update(VehicleType $vehicleType): int
    {
        $stmt = $this->connection->prepare(
            'UPDATE vehicle_types SET type = :type WHERE id = :id'
        );
        $this->bindStatement($stmt, $vehicleType);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * delete deletes a user by id
     *
     * @param int $id
     * @return int rows affected
     * @throws \PDOException
     */
    public function delete(int $id): int
    {
        $this->connection->exec('SET foreign_key_checks = 0'); // disable foreign key checks during deletion
        $stmt = $this->connection->prepare('DELETE FROM vehicle_types WHERE id = :id');
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $this->connection->exec('SET foreign_key_checks = 1'); // enabke foreign key checks after deletion
        return $stmt->rowCount();
    }

    /**
     *  ----------------------------------------------------------------
     *  Utility methods
     *  ----------------------------------------------------------------
     */

    /**
     * bindStatement binds parameters into the given statement 
     * 
     * @param \PDOStatement  $stmt the prepared statement to be bind
     * @param VehicleType $vehicleType
     * @return \PDOStatement
     */
    private function bindStatement(\PDOStatement $stmt, VehicleType $vehicleType): \PDOStatement
    {
        $id = $vehicleType->getId();
        $type = $vehicleType->getType();
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':type', $type, \PDO::PARAM_STR);
        return $stmt;
    }
}
