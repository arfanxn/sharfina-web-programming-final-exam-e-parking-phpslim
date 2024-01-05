<?php

namespace App\Models;

use App\Interfaces\ArrayableInterface;
use App\Interfaces\ResourceableInterface;
use App\Resources\ParkedVehicleResource;
use DateTime;

class ParkedVehicle extends Model implements ResourceableInterface
{
    private string $id;
    private Vehicle $vehicle;
    private string $vehicleId;
    private string $plateNumber;
    private User $enteredByUser;
    private string $enteredByUserId;
    private DateTime $enteredAt;
    private ?User $leftByUser;
    private ?string $leftByUserId;
    private ?DateTime $leftAt;

    public function __construct()
    {
        $this->setTable('parked_vehicles')->setColumns([
            'id',
            'vehicle_id',
            'plate_number',
            'entered_by_user_id',
            'entered_at',
            'left_by_user_id',
            'left_at',
        ]);
    }

    /**
     *  ----------------------------------------------------------------
     *  Getters and setters
     *  ----------------------------------------------------------------
     */

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Vehicle
     */
    public function getVehicle(): Vehicle
    {
        return $this->vehicle;
    }
    /**
     * @param Vehicle $vehicle
     * @return void
     */
    public function setVehicle(Vehicle $vehicle): void
    {
        $this->vehicle = $vehicle;
    }

    /**
     * @return string
     */
    public function getVehicleId(): string
    {
        return $this->vehicleId;
    }
    /**
     * @param string $vehicleId
     * @return void
     */
    public function setVehicleId(string $vehicleId): void
    {
        $this->vehicleId = $vehicleId;
    }

    /**
     * @return string
     */
    public function getPlateNumber(): string
    {
        return $this->plateNumber;
    }
    /**
     * @param string $plateNumber
     * @return void
     */
    public function setPlateNumber(string $plateNumber): void
    {
        $this->plateNumber = $plateNumber;
    }

    /**
     * @return User
     */
    public function getEnteredByUser(): User
    {
        return $this->enteredByUser;
    }
    /**
     * @param User $enteredByUser
     * @return void
     */
    public function setEnteredByUser(User $enteredByUser): void
    {
        $this->enteredByUser = $enteredByUser;
    }
    /**
     * @return string
     */
    public function getEnteredByUserId(): string
    {
        return $this->enteredByUserId;
    }
    /**
     * @param string $enteredByUserId
     * @return void
     */
    public function setEnteredByUserId(string $enteredByUserId): void
    {
        $this->enteredByUserId = $enteredByUserId;
    }

    /**
     * @return ?User
     */
    public function getLeftByUser(): ?User
    {
        return $this->leftByUser ?? null;
    }
    /**
     * @param ?User $leftByUser
     * @return void
     */
    public function setLeftByUser(?User $leftByUser): void
    {
        $this->leftByUser = $leftByUser;
    }
    /**
     * @return ?string
     */
    public function getLeftByUserId(): ?string
    {
        return $this->leftByUserId ?? null;
    }
    /**
     * @param ?string $leftByUserId
     * @return void
     */
    public function setLeftByUserId(?string $leftByUserId): void
    {
        $this->leftByUserId = $leftByUserId;
    }

    /**
     * @return DateTime
     */
    public function getEnteredAt(): DateTime
    {
        return $this->enteredAt;
    }
    /**
     * @param mixed $enteredAt
     * @return void
     */
    public function setEnteredAt(mixed $enteredAt): void
    {
        $this->enteredAt = is_string($enteredAt) ? new DateTime($enteredAt) : $enteredAt;
    }

    /**
     * @return ?DateTime
     */
    public function getLeftAt(): ?DateTime
    {
        return $this->leftAt ?? null;
    }
    /**
     * @param mixed $leftAt
     * @return void
     */
    public function setLeftAt(mixed $leftAt): void
    {
        $this->leftAt = is_string($leftAt) ? new DateTime($leftAt) : $leftAt;
    }

    /**
     *  ----------------------------------------------------------------
     *  Other methods
     *  ----------------------------------------------------------------
     */

    public function toResource(): ArrayableInterface
    {
        return new ParkedVehicleResource($this);
    }
}
