<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Repositories\VehicleRepository;
use App\Resources\Pagination;
use App\Resources\VehicleResource;
use App\Forms\PaginationForm;
use App\Repositories\VehicleColorRepository;
use App\Repositories\VehicleRatePerHourRepository;
use App\Repositories\VehicleTypeRepository;

class VehicleService extends Service
{
    use \App\Traits\ContainerAwareTrait;

    private VehicleRepository $vehicleRepository;
    private VehicleColorRepository $vehicleColorRepository;
    private VehicleTypeRepository $vehicleTypeRepository;
    private VehicleRatePerHourRepository $vehicleRPHRepository;

    public function __construct(
        VehicleRepository $vehicleRepository,
        VehicleColorRepository $vehicleColorRepository,
        VehicleTypeRepository $vehicleTypeRepository,
        VehicleRatePerHourRepository $vehicleRPHRepository
    ) {
        $this->vehicleRepository = $vehicleRepository;
        $this->vehicleColorRepository = $vehicleColorRepository;
        $this->vehicleTypeRepository = $vehicleTypeRepository;
        $this->vehicleRPHRepository = $vehicleRPHRepository;
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
        $resources = array_map(function (Vehicle $vehicle) {
            return new VehicleResource($vehicle);
        }, $vehicles);
        return $resources;
    }

    /**
     * paginate 
     *
     * @param PaginationForm $form
     * @return Pagination
     * @throws \PDOException
     */
    public function paginate(PaginationForm $form): Pagination
    {
        $vehicles = $this->vehicleRepository->paginate($form->getLimit(), $form->getOffset(), $form->getKeyword());
        $resources = array_map(function (Vehicle $vehicle) {
            return new VehicleResource($vehicle);
        }, $vehicles);
        return Pagination::new()->fillMetadata($form->getPage(), $form->getPerPage(), $form->getKeyword())
            ->setData($resources);
    }

    /**
     * find finds by id
     *
     * @param string $id
     * @return VehicleResource
     * @throws \PDOException
     * @throws \App\Exceptions\HttpException
     */
    public function find(string $id): VehicleResource
    {
        $vehicle = $this->vehicleRepository->find($id);
        if (is_null($vehicle)) {
            $this->throwDataNotFoundHttpException();
        }
        $vehicleColor = $this->vehicleColorRepository->find($vehicle->getVehicleColorId());
        $vehicle->setVehicleColor($vehicleColor);
        $vehicleType = $this->vehicleTypeRepository->find($vehicle->getVehicleTypeId());
        $vehicle->setVehicleType($vehicleType);
        $vehicleRPH = $this->vehicleRPHRepository->find($vehicle->getVehicleRatePerHourId());
        $vehicle->setVehicleRatePerHour($vehicleRPH);

        return new VehicleResource($vehicle);
    }

    /**
     * ------------------------------------------------------------------------------------------------
     * Class utility methods
     * ------------------------------------------------------------------------------------------------
     */

    /**
     * throwDataNotFoundHttpException
     *
     * @return never
     * @throws \App\Exceptions\HttpException
     */
    public function throwDataNotFoundHttpException()
    {
        throw \App\Exceptions\HttpException::new()
            ->setStatusCode(404)
            ->__setMessage('Data was not found.')
            ->setRedirectionUrlStr('/users');;
    }
}
