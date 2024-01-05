<?php

namespace App\Services;

use App\Models\VehicleColor;
use App\Repositories\VehicleColorRepository;
use App\Resources\VehicleColorResource;

class VehicleColorService extends Service
{
    use \App\Traits\ContainerAwareTrait;

    private VehicleColorRepository $vehicleRepository;

    public function __construct(VehicleColorRepository $vehicleRepository)
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
        $resources = array_map(function (VehicleColor $vehicle) {
            return new VehicleColorResource($vehicle);
        }, $vehicles);
        return $resources;
    }
}
