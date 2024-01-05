<?php

namespace App\Models;

class VehicleColor extends Model
{
    private string $id;
    private string $name;
    private string $hexCode;

    public function __construct()
    {
        $this->setTable('vehicle_colors')->setColumns([
            'id',
            'name',
            'hex_code',
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getHexCode(): string
    {
        return $this->hexCode;
    }
    /**
     * @param string $hexCode
     * @return void
     */
    public function setHexCode(string $hexCode): void
    {
        $this->hexCode = $hexCode;
    }

    /**
     *  ----------------------------------------------------------------
     *  Other methods
     *  ----------------------------------------------------------------
     */
}
