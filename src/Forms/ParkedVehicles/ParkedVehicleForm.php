<?php

namespace App\Forms\ParkedVehicles;

use DateTime;

class ParkedVehicleForm
{
    private string $id;
    private string $vehicleId;
    private string $plateNumber;
    private string $enteredByUserId;
    private DateTime $enteredAt;
    private ?string $leftByUserId;
    private ?DateTime $leftAt;

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
     * @return string
     */
    public function getEnteredByUserId(): string
    {
        return $this->enteredByUserId;
    }
    /**
     * @param string $enteredByUserId
     * @return self
     */
    public function setEnteredByUserId(string $enteredByUserId): self
    {
        $this->enteredByUserId = $enteredByUserId;
        return $this;
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
    public function setLeftByUserId(?string $leftByUserId): self
    {
        $this->leftByUserId = $leftByUserId;
        return $this;
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
     * @return self
     */
    public function setEnteredAt(mixed $enteredAt): self
    {
        if (is_null($enteredAt) || $enteredAt == '') {
            $this->enteredAt = null;
        } else if (is_string($enteredAt)) {
            $this->enteredAt = \DateTime::createFromFormat('Y-m-d', $enteredAt);
        } else if ($enteredAt instanceof \DateTime) {
            $this->enteredAt = $enteredAt;
        }
        return $this;
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
     * @return self
     */
    public function setLeftAt(mixed $leftAt): self
    {
        if (is_null($leftAt) || $leftAt == '') {
            $this->leftAt = null;
        } else if (is_string($leftAt)) {
            $this->leftAt = \DateTime::createFromFormat('Y-m-d', $leftAt);
        } else if ($leftAt instanceof \DateTime) {
            $this->leftAt = $leftAt;
        }
        return $this;
    }
}
