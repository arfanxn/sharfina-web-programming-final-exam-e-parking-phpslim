<?php

namespace App\Repositories;

use App\Models\ParkedVehicle;
use Stringy\Stringy;

class ParkedVehicleRepository extends Repository
{

    /**
     * paginate gets rows of parked_vehicles with pagination
     *
     * @param int $limit
     * @param int $offset
     * @param ?string $keyword
     * @return array array of parked_vehicles
     * @throws \PDOException
     */
    public function paginate(int $limit, int $offset, ?string $keyword = null): array
    {
        $isWithKeyword = (!is_null($keyword) && $keyword != '');
        $stmt = $this->connection->prepare((new Stringy())
            ->append('SELECT * FROM parked_vehicles')
            ->append($isWithKeyword ? ' WHERE plate_number LIKE :keyword'  : '')
            ->append(' ORDER BY `entered_at` DESC LIMIT :limit OFFSET :offset')
            ->toString());
        if ($isWithKeyword) {
            $keyword = '%' . $keyword . '%';
            $stmt->bindParam(':keyword', $keyword, \PDO::PARAM_STR);
        }
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        $parkedVehicles = array();
        foreach ($rows as $row) {
            array_push($parkedVehicles, (new ParkedVehicle())->hydrate($row));
        }

        return $parkedVehicles;
    }

    /**
     * find finds a user by id
     *
     * @param string $id
     * @return ?ParkedVehicle
     * @throws \PDOException
     */
    public function find(string $id): ?ParkedVehicle
    {
        $stmt = $this->connection->prepare('SELECT * FROM parked_vehicles WHERE id = :id');
        $stmt->bindParam(':id', $id, \PDO::PARAM_STR);
        $stmt->execute();

        $data = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;

        return is_null($data) ? null : (new ParkedVehicle())->hydrate($data);
    }

    /**
     * findByPlateNumber finds a parked vehicle by plate number
     *
     * @param string $plateNumber
     * @return ?ParkedVehicle
     * @throws \PDOException
     */
    public function findByPlateNumber(string $plateNumber): ?ParkedVehicle
    {
        $stmt = $this->connection->prepare('SELECT * FROM parked_vehicles WHERE plate_number = :plate_number');
        $stmt->bindParam(':plate_number', $plateNumber, \PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;

        return is_null($row) ? null : (new ParkedVehicle())->hydrate($row);
    }

    /**
     * findLatest get the latest inserted row 
     *
     * @return ?ParkedVehicle
     * @throws \PDOException
     */
    public function findLatest(): ?ParkedVehicle
    {
        $stmt = $this->connection->prepare('SELECT * FROM parked_vehicles ORDER BY entered_at DESC LIMIT 1');
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        return is_null($row) ? null : (new ParkedVehicle())->hydrate($row);
    }

    /**
     * create 
     *
     * @param ParkedVehicle $parkedVehicle
     * @return int rows affected
     * @throws \PDOException
     */
    public function create(ParkedVehicle $parkedVehicle): int
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO parked_vehicles (id, vehicle_id, plate_number, entered_by_user_id, entered_at, left_by_user_id, left_at) VALUES (:id, :vehicle_id, :plate_number, :entered_by_user_id, :entered_at, :left_by_user_id, :left_at);'
        );
        $this->bindStatement($stmt, $parkedVehicle);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * update updates a user by id
     *
     * @param ParkedVehicle $parkedVehicle
     * @return int rows affected
     * @throws \PDOException
     */
    public function update(ParkedVehicle $parkedVehicle): int
    {
        $stmt = $this->connection->prepare(
            'UPDATE parked_vehicles SET vehicle_id = :vehicle_id, plate_number = :plate_number, entered_by_user_id = :entered_by_user_id, entered_at = :entered_at, left_by_user_id = :left_by_user_id, left_at = :left_at WHERE id = :id'
        );
        $this->bindStatement($stmt, $parkedVehicle);
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
        $stmt = $this->connection->prepare('DELETE FROM parked_vehicles WHERE id = :id');
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
     * @param ParkedVehicle $parkedVehicle
     * @return \PDOStatement
     */
    private function bindStatement(\PDOStatement $stmt, ParkedVehicle $parkedVehicle): \PDOStatement
    {
        $id = $parkedVehicle->getId();
        $vehicleId = $parkedVehicle->getVehicleId();
        $plateNumber = $parkedVehicle->getPlateNumber();
        $enteredByUserId = $parkedVehicle->getEnteredByUserId();
        $enteredAt = $parkedVehicle->getEnteredAt();
        $enteredAtStr = $enteredAt ? $enteredAt->format('Y-m-d H:i:s') : null;
        $leftByUserId = $parkedVehicle->getLeftByUserId();
        $leftAt = $parkedVehicle->getLeftAt();
        $leftAtStr = $leftAt ? $leftAt->format('Y-m-d H:i:s') : null;
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':vehicle_id', $vehicleId, \PDO::PARAM_STR);
        $stmt->bindParam(':plate_number', $plateNumber, \PDO::PARAM_STR);
        $stmt->bindParam(':entered_by_user_id', $enteredByUserId, \PDO::PARAM_STR);
        $stmt->bindParam(':entered_at', $enteredAtStr, \PDO::PARAM_STR);
        $stmt->bindParam(':left_by_user_id', $leftByUserId, \PDO::PARAM_STR);
        $stmt->bindParam(':left_at', $leftAtStr, \PDO::PARAM_STR);
        return $stmt;
    }
}
