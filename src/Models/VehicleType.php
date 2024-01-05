<?php

namespace App\Models;

class VehicleType extends Model
{
    private string $id;
    private string $type;

    public function __construct()
    {
        $this->setTable('vehicle_types')->setColumns([
            'id',
            'type',
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
    public function getType(): string
    {
        return $this->type;
    }
    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     *  ----------------------------------------------------------------
     *  Other methods
     *  ----------------------------------------------------------------
     */
}
