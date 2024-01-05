<?php

namespace App\Repositories;

use App\Models\VehicleRatePerHour;
use Stringy\Stringy;

class VehicleRatePerHourRepository extends Repository
{

    /**
     * all gets rows of vehicle_rate_per_hours
     *
     * @return array array of vehicle_rate_per_hours
     * @throws \PDOException
     */
    public function all(): array
    {
        $stmt = $this->connection->prepare((new Stringy())
            ->append('SELECT * FROM vehicle_rate_per_hours')
            ->append(' ORDER BY vehicle_rate_per_hours.rate ASC')
            ->toString());
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        $vehicleRPHs = array();
        foreach ($rows as $row) {
            array_push($vehicleRPHs, (new VehicleRatePerHour())->hydrate($row));
        }

        return $vehicleRPHs;
    }

    /**
     * find finds a user by id
     *
     * @param int $id
     * @return ?VehicleRatePerHour
     * @throws \PDOException
     */
    public function find(int $id): ?VehicleRatePerHour
    {
        $stmt = $this->connection->prepare('SELECT * FROM vehicle_rate_per_hours WHERE id = :id');
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;

        return is_null($data) ? null : (new VehicleRatePerHour())->hydrate($data);
    }

    /**
     * findLatest get the latest inserted row 
     *
     * @return ?VehicleRatePerHour
     * @throws \PDOException
     */
    public function findLatest(): ?VehicleRatePerHour
    {
        $stmt = $this->connection->prepare('SELECT * FROM vehicle_rate_per_hours ORDER BY created_at DESC LIMIT 1');
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        return is_null($row) ? null : (new VehicleRatePerHour())->hydrate($row);
    }

    /**
     * create 
     *
     * @param VehicleRatePerHour $vehicleRPH
     * @return int rows affected
     * @throws \PDOException
     */
    public function create(VehicleRatePerHour $vehicleRPH): int
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO vehicle_rate_per_hours (id, rate) VALUES (:id, :rate);'
        );
        $this->bindStatement($stmt, $vehicleRPH);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * update updates a user by id
     *
     * @param VehicleRatePerHour $vehicleRPH
     * @return int rows affected
     * @throws \PDOException
     */
    public function update(VehicleRatePerHour $vehicleRPH): int
    {
        $stmt = $this->connection->prepare(
            'UPDATE vehicle_rate_per_hours SET rate = :rate WHERE id = :id'
        );
        $this->bindStatement($stmt, $vehicleRPH);
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
        $stmt = $this->connection->prepare('DELETE FROM vehicle_rate_per_hours WHERE id = :id');
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
     * @param VehicleRatePerHour $vehicleRPH
     * @return \PDOStatement
     */
    private function bindStatement(\PDOStatement $stmt, VehicleRatePerHour $vehicleRPH): \PDOStatement
    {
        $id = $vehicleRPH->getId();
        $rate = $vehicleRPH->getRate();
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':rate', $rate, \PDO::PARAM_STR);
        return $stmt;
    }
}
