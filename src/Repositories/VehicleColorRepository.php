<?php

namespace App\Repositories;

use App\Models\VehicleColor;
use Stringy\Stringy;

class VehicleColorRepository extends Repository
{

    /**
     * all gets rows of vehicle_colors
     *
     * @return array array of vehicle_colors
     * @throws \PDOException
     */
    public function all(): array
    {
        $stmt = $this->connection->prepare((new Stringy())
            ->append('SELECT * FROM vehicle_colors')
            ->append(' ORDER BY vehicle_colors.name ASC')
            ->toString());
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        $vehicleColors = array();
        foreach ($rows as $row) {
            array_push($vehicleColors, (new VehicleColor())->hydrate($row));
        }

        return $vehicleColors;
    }

    /**
     * find finds a user by id
     *
     * @param int $id
     * @return ?VehicleColor
     * @throws \PDOException
     */
    public function find(int $id): ?VehicleColor
    {
        $stmt = $this->connection->prepare('SELECT * FROM vehicle_colors WHERE id = :id');
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;

        return is_null($data) ? null : (new VehicleColor())->hydrate($data);
    }

    /**
     * findLatest get the latest inserted row 
     *
     * @return ?VehicleColor
     * @throws \PDOException
     */
    public function findLatest(): ?VehicleColor
    {
        $stmt = $this->connection->prepare('SELECT * FROM vehicle_colors ORDER BY created_at DESC LIMIT 1');
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        return is_null($row) ? null : (new VehicleColor())->hydrate($row);
    }

    /**
     * create 
     *
     * @param VehicleColor $vehicleColor
     * @return int rows affected
     * @throws \PDOException
     */
    public function create(VehicleColor $vehicleColor): int
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO vehicle_colors (id, name, hex_code) VALUES (:id, :name, :hex_code);'
        );
        $this->bindStatement($stmt, $vehicleColor);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * update updates a user by id
     *
     * @param VehicleColor $vehicleColor
     * @return int rows affected
     * @throws \PDOException
     */
    public function update(VehicleColor $vehicleColor): int
    {
        $stmt = $this->connection->prepare(
            'UPDATE vehicle_colors SET name = :name, hex_code = :hex_code WHERE id = :id'
        );
        $this->bindStatement($stmt, $vehicleColor);
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
        $stmt = $this->connection->prepare('DELETE FROM vehicle_colors WHERE id = :id');
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
     * @param VehicleColor $vehicleColor
     * @return \PDOStatement
     */
    private function bindStatement(\PDOStatement $stmt, VehicleColor $vehicleColor): \PDOStatement
    {
        $id = $vehicleColor->getId();
        $name = $vehicleColor->getName();
        $hexCode = $vehicleColor->getHexCode();
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
        $stmt->bindParam(':hex_code', $hexCode, \PDO::PARAM_STR);
        return $stmt;
    }
}
