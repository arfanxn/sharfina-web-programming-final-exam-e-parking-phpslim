<?php

namespace App\Resources;

use App\Interfaces\ArrayableInterface;
use App\Models\VehicleType;

class VehicleTypeResource implements ArrayableInterface
{
    private VehicleType $model;

    public function __construct(VehicleType $model)
    {
        $this->model = $model;
    }

    public function toArray(): array
    {
        $model = $this->model;
        return [
            'id' => $model->getId(),
            'type' => $model->getType(),
        ];
    }
}
