<?php

namespace App\Models;

class VehicleRatePerHour extends Model
{
    private string $id;
    private float $rate;

    public function __construct()
    {
        $this->setTable('vehicle_rate_per_hours')->setColumns([
            'id',
            'rate',
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
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }
    /**
     * @param float $rate
     * @return void
     */
    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }

    /**
     *  ----------------------------------------------------------------
     *  Other methods
     *  ----------------------------------------------------------------
     */
}
