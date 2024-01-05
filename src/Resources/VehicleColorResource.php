<?php

namespace App\Resources;

use App\Interfaces\ArrayableInterface;
use App\Models\VehicleColor;

class VehicleColorResource implements ArrayableInterface
{
    private VehicleColor $model;

    public function __construct(VehicleColor $model)
    {
        $this->model = $model;
    }

    public function toArray(): array
    {
        $model = $this->model;
        return [
            'id' => $model->getId(),
            'name' => $model->getName(),
            'hex_code' => $model->getHexCode(),
        ];
    }
}
