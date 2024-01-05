<?php

namespace App\Services;

use App\Models\VehicleType;
use App\Repositories\VehicleTypeRepository;
use App\Resources\VehicleTypeResource;

class VehicleTypeService extends Service
{
    use \App\Traits\ContainerAwareTrait;

    private VehicleTypeRepository $vehicleRepository;

    public function __construct(VehicleTypeRepository $vehicleRepository)
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
        $resources = array_map(function (VehicleType $vehicle) {
            return new VehicleTypeResource($vehicle);
        }, $vehicles);
        return $resources;
    }
}
