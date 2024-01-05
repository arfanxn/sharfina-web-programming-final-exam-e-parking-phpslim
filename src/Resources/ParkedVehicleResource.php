<?php

namespace App\Resources;

use App\Interfaces\ArrayableInterface;
use App\Models\ParkedVehicle;

class ParkedVehicleResource implements ArrayableInterface
{
    private ParkedVehicle $model;

    public function __construct(ParkedVehicle $model)
    {
        $this->model = $model;
    }

    public function toArray(): array
    {
        $model = $this->model;
        return [
            'id' => $model->getId(),
            'vehicle_id' => $model->getVehicleId(),
            'plate_number' => $model->getPlateNumber(),
            'entered_by_user_id' => $model->getEnteredByUserId(),
            'entered_at' => $model->getEnteredAt() ? $model->getEnteredAt()->format('Y-m-d H:i:s') : null,
            'left_by_user_id' => $model->getLeftByUserId(),
            'left_at' => $model->getLeftAt() ? $model->getLeftAt()->format('Y-m-d H:i:s') : null,
        ];
    }
}
