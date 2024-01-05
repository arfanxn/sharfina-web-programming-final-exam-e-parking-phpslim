<?php

namespace App\Resources;

use App\Interfaces\ArrayableInterface;
use App\Models\VehicleRatePerHour;

class VehicleRatePerHourResource implements ArrayableInterface
{
    private VehicleRatePerHour $model;

    public function __construct(VehicleRatePerHour $model)
    {
        $this->model = $model;
    }

    public function toArray(): array
    {
        $model = $this->model;
        return [
            'id' => $model->getId(),
            'rate' => $model->getRate(),
        ];
    }
}
