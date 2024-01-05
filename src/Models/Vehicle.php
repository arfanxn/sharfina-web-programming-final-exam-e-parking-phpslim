<?php

namespace App\Models;

use App\Interfaces\ArrayableInterface;
use App\Interfaces\ResourceableInterface;
use App\Resources\VehicleResource;
use DateTime;

class Vehicle extends Model implements ResourceableInterface
{
    private string $id;
    private VehicleColor $vehicleColor;
    private string $vehicleColorId;
    private VehicleType $vehicleType;
    private string $vehicleTypeId;
    private VehicleRatePerHour $vehicleRatePerHour;
    private string $vehicleRatePerHourId;
    private DateTime $createdAt;
    private ?DateTime $updatedAt;

    public function __construct()
    {
        $this->setTable('vehicles')->setColumns([
            'id',
            'vehicle_color_id',
            'vehicle_type_id',
            'vehicle_rate_per_hour_id',
            'created_at',
            'updated_at',
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
     * @return VehicleColor
     */
    public function getVehicleColor(): VehicleColor
    {
        return $this->vehicleColor;
    }
    /**
     * @param VehicleColor $vehicleColor
     * @return void
     */
    public function setVehicleColor(VehicleColor $vehicleColor): void
    {
        $this->vehicleColor = $vehicleColor;
    }
    /**
     * @return string
     */
    public function getVehicleColorId(): string
    {
        return $this->vehicleColorId;
    }
    /**
     * @param string $vehicleColorId
     * @return void
     */
    public function setVehicleColorId(string $vehicleColorId): void
    {
        $this->vehicleColorId = $vehicleColorId;
    }

    /**
     * @return VehicleType
     */
    public function getVehicleType(): VehicleType
    {
        return $this->vehicleType;
    }
    /**
     * @param VehicleType $vehicleType
     * @return void
     */
    public function setVehicleType(VehicleType $vehicleType): void
    {
        $this->vehicleType = $vehicleType;
    }
    /**
     * @return string
     */
    public function getVehicleTypeId(): string
    {
        return $this->vehicleTypeId;
    }
    /**
     * @param string $vehicleTypeId
     * @return void
     */
    public function setVehicleTypeId(string $vehicleTypeId): void
    {
        $this->vehicleTypeId = $vehicleTypeId;
    }

    /**
     * @return VehicleRatePerHour
     */
    public function getVehicleRatePerHour(): VehicleRatePerHour
    {
        return $this->vehicleRatePerHour;
    }
    /**
     * @param VehicleRatePerHour $vehicleRatePerHour
     * @return void
     */
    public function setVehicleRatePerHour(VehicleRatePerHour $vehicleRatePerHour): void
    {
        $this->vehicleRatePerHour = $vehicleRatePerHour;
    }
    /**
     * @return string
     */
    public function getVehicleRatePerHourId(): string
    {
        return $this->vehicleRatePerHourId;
    }
    /**
     * @param string $vehicleRatePerHourId
     * @return void
     */
    public function setVehicleRatePerHourId(string $vehicleRatePerHourId): void
    {
        $this->vehicleRatePerHourId = $vehicleRatePerHourId;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
    /**
     * @param mixed $createdAt
     * @return void
     */
    public function setCreatedAt(mixed $createdAt): void
    {
        $this->createdAt = is_string($createdAt) ? new DateTime($createdAt) : $createdAt;
    }

    /**
     * @return ?DateTime
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt ?? null;
    }
    /**
     * @param mixed $updatedAt
     * @return void
     */
    public function setUpdatedAt(mixed $updatedAt): void
    {
        $this->updatedAt = is_string($updatedAt) ? new DateTime($updatedAt) : $updatedAt;
    }

    /**
     *  ----------------------------------------------------------------
     *  Other methods
     *  ----------------------------------------------------------------
     */

    public function toResource(): ArrayableInterface
    {
        return new VehicleResource($this);
    }
}
