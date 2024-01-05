<?php

namespace App\Repositories;

use App\Models\Vehicle;
use Stringy\Stringy;

class VehicleRepository extends Repository
{

    /**
     * all gets rows of vehicles
     *
     * @return array array of vehicles
     * @throws \PDOException
     */
    public function all(): array
    {
        $stmt = $this->connection->prepare((new Stringy())
            ->append('SELECT * FROM vehicles')
            ->append(' ORDER BY vehicles.created_at')
            ->toString());
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        $vehicles = array();
        foreach ($rows as $row) {
            array_push($vehicles, (new Vehicle())->hydrate($row));
        }

        return $vehicles;
    }

    /**
     * paginate gets rows of vehicles with pagination
     *
     * @param int $limit
     * @param int $offset
     * @param ?string $keyword
     * @return array array of vehicles
     * @throws \PDOException
     */
    public function paginate(int $limit, int $offset, ?string $keyword = null): array
    {
        $isWithKeyword = (!is_null($keyword) && $keyword != '');
        $stmt = $this->connection->prepare((new Stringy())
            ->append('SELECT * FROM vehicles')
            ->append(' LEFT JOIN vehicle_types ON vehicle_types.id = vehicles.vehicle_type_id')
            ->append($isWithKeyword ? ' WHERE vehicle_types.type LIKE :keyword'  : '')
            ->append(' ORDER BY vehicles.created_at DESC LIMIT :limit OFFSET :offset')
            ->toString());
        if ($isWithKeyword) {
            $keyword = '%' . $keyword . '%';
            $stmt->bindParam(':keyword', $keyword, \PDO::PARAM_STR);
        }
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        $vehicles = array();
        foreach ($rows as $row) {
            array_push($vehicles, (new Vehicle())->hydrate($row));
        }

        return $vehicles;
    }

    /**
     * find finds a user by id
     *
     * @param int $id
     * @return ?Vehicle
     * @throws \PDOException
     */
    public function find(int $id): ?Vehicle
    {
        $stmt = $this->connection->prepare('SELECT * FROM vehicles WHERE id = :id');
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;

        return is_null($data) ? null : (new Vehicle())->hydrate($data);
    }

    /**
     * findByColorIdNTypeId
     *
     * @param int $colorId
     * @param int $typeId
     * @return ?Vehicle
     * @throws \PDOException
     */
    public function findByColorIdNTypeId(int $colorId, int $typeId): ?Vehicle
    {
        $stmt = $this->connection->prepare('SELECT * FROM vehicles WHERE vehicle_color_id = :vehicle_color_id AND  vehicle_type_id = :vehicle_type_id');
        $stmt->bindParam(':vehicle_color_id', $colorId, \PDO::PARAM_STR);
        $stmt->bindParam(':vehicle_type_id', $typeId, \PDO::PARAM_STR);
        $stmt->execute();

        $data = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;

        return is_null($data) ? null : (new Vehicle())->hydrate($data);
    }

    /**
     * findLatest get the latest inserted row 
     *
     * @return ?Vehicle
     * @throws \PDOException
     */
    public function findLatest(): ?Vehicle
    {
        $stmt = $this->connection->prepare('SELECT * FROM vehicles ORDER BY created_at DESC LIMIT 1');
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        return is_null($row) ? null : (new Vehicle())->hydrate($row);
    }

    /**
     * create 
     *
     * @param Vehicle $vehicle
     * @return int rows affected
     * @throws \PDOException
     */
    public function create(Vehicle $vehicle): int
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO vehicles (id, vehicle_color_id, vehicle_type_id, vehicle_rate_per_hour_id, created_at, updated_at) VALUES (:id, :vehicle_color_id, :vehicle_type_id, :vehicle_rate_per_hour_id, :created_at, :updated_at);'
        );
        $this->bindStatement($stmt, $vehicle);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * update updates a user by id
     *
     * @param Vehicle $vehicle
     * @return int rows affected
     * @throws \PDOException
     */
    public function update(Vehicle $vehicle): int
    {
        $stmt = $this->connection->prepare(
            'UPDATE vehicles SET vehicle_id = :vehicle_id, plate_number = :plate_number, entered_by_user_id = :entered_by_user_id, entered_at = :entered_at, left_by_user_id = :left_by_user_id, left_at = :left_at WHERE id = :id'
        );
        $this->bindStatement($stmt, $vehicle);
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
        $stmt = $this->connection->prepare('DELETE FROM vehicles WHERE id = :id');
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
     * @param Vehicle $vehicle
     * @return \PDOStatement
     */
    private function bindStatement(\PDOStatement $stmt, Vehicle $vehicle): \PDOStatement
    {
        $id = $vehicle->getId();
        $vehicleColorId = $vehicle->getVehicleColorId();
        $vehicleTypeId = $vehicle->getVehicleTypeId();
        $vehicleRatePerHourId = $vehicle->getVehicleRatePerHourId();
        $createdAt = $vehicle->getCreatedAt();
        $createdAtStr = $createdAt ? $createdAt->format('Y-m-d H:i:s') : null;
        $updatedAt = $vehicle->getUpdatedAt();
        $updatedAtStr = $updatedAt ? $updatedAt->format('Y-m-d H:i:s') : null;
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':vehicle_color_id', $vehicleColorId, \PDO::PARAM_STR);
        $stmt->bindParam(':vehicle_type_id', $vehicleTypeId, \PDO::PARAM_STR);
        $stmt->bindParam(':vehicle_rate_per_hour_id', $vehicleRatePerHourId, \PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $createdAtStr, \PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updatedAtStr, \PDO::PARAM_STR);
        return $stmt;
    }
}
