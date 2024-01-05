<?php

namespace App\Services;

use App\Models\VehicleRatePerHour;
use App\Repositories\VehicleRatePerHourRepository;
use App\Resources\VehicleRatePerHourResource;

class VehicleRatePerHourService extends Service
{
    use \App\Traits\ContainerAwareTrait;

    private VehicleRatePerHourRepository $vehicleRepository;

    public function __construct(VehicleRatePerHourRepository $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * all  
     *
     * @return array
     * @throws \PDOException
     */
    public function all(): array
    {
        $vehicles = $this->vehicleRepository->all();
        $resources = array_map(function (VehicleRatePerHour $vehicle) {
            return new VehicleRatePerHourResource($vehicle);
        }, $vehicles);
        return $resources;
    }
}
