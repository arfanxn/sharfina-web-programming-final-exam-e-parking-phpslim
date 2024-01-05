<?php

namespace App\Resources;

use App\Interfaces\ArrayableInterface;
use App\Models\Vehicle;

class VehicleResource implements ArrayableInterface
{
    private Vehicle $model;

    public function __construct(Vehicle $model)
    {
        $this->model = $model;
    }

    public function toArray(): array
    {
        $model = $this->model;
        $arr =  [
            'id' => $model->getId(),
            'vehicle_color_id' => $model->getVehicleColorId(),
            'vehicle_type_id' => $model->getVehicleTypeId(),
            'vehicle_rate_per_hour_id' => $model->getVehicleTypeId(),
            'created_at' => $model->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $model->getUpdatedAt() ? $model->getUpdatedAt()->format('Y-m-d H:i:s') : null,
        ];

        if ($model->getVehicleColor() != null) {
            $arr = array_merge($arr, [
                'vehicle_color' => (new VehicleColorResource($model->getVehicleColor()))->toArray()
            ]);
        }
        if ($model->getVehicleType() != null) {
            $arr = array_merge($arr, [
                'vehicle_type' => (new VehicleTypeResource($model->getVehicleType()))->toArray()
            ]);
        }
        if ($model->getVehicleRatePerHour() != null) {
            $arr = array_merge($arr, [
                'vehicle_rate_per_hour' => (new VehicleRatePerHourResource($model->getVehicleRatePerHour()))->toArray()
            ]);
        }

        return $arr;
    }
}
