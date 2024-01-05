<?php

namespace App\Services;

use App\Forms\PaginationForm;
use App\Forms\ParkedVehicles\EnterForm;
use App\Forms\ParkedVehicles\UpdateForm;
use App\Helpers\Session;
use App\Models\ParkedVehicle;
use App\Repositories\ParkedVehicleRepository;
use App\Repositories\VehicleColorRepository;
use App\Repositories\VehicleRepository;
use App\Repositories\VehicleTypeRepository;
use App\Resources\Pagination;
use DateTime;
use App\Resources\ParkedVehicleResource;

class ParkedVehicleService extends Service
{
    use \App\Traits\ContainerAwareTrait;

    private ParkedVehicleRepository $parkedVehicleRepository;
    private VehicleRepository $vehicleRepository;

    public function __construct(
        ParkedVehicleRepository $parkedVehicleRepository,
        VehicleRepository $vehicleRepository,
    ) {
        $this->parkedVehicleRepository = $parkedVehicleRepository;
        $this->vehicleRepository = $vehicleRepository;
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
        $parkedVehicles = $this->parkedVehicleRepository->paginate($form->getLimit(), $form->getOffset(), $form->getKeyword());
        $resources = array_map(function (ParkedVehicle $parkedVehicle) {
            return new ParkedVehicleResource($parkedVehicle);
        }, $parkedVehicles);
        return Pagination::new()->fillMetadata($form->getPage(), $form->getPerPage(), $form->getKeyword())->setData($resources);
    }

    /**
     * find finds by id
     *
     * @param string $id
     * @return ParkedVehicleResource
     * @throws \PDOException
     * @throws \App\Exceptions\HttpException
     */
    public function find(string $id): ParkedVehicleResource
    {
        $parkedVehicle = $this->parkedVehicleRepository->find($id);
        if (is_null($parkedVehicle)) {
            $this->throwDataNotFoundHttpException();
        }
        return new ParkedVehicleResource($parkedVehicle);
    }

    /**
     * enter creates a user
     *
     * @param EnterForm $form
     * @return ParkedVehicleResource
     * @throws \PDOException
     */
    public function enter(EnterForm $form): ParkedVehicleResource
    {
        $latestParkedVehicle = $this->parkedVehicleRepository->findLatest();

        $vehicle = $this->vehicleRepository->findByColorIdNTypeId(
            $form->getVehicleColorId(),
            $form->getVehicleTypeId()
        );
        if (is_null($vehicle)) {
            $this->throwDataNotFoundHttpException();
        }

        $parkedVehicle = new ParkedVehicle();
        $parkedVehicle->setId(($latestParkedVehicle->getId() ?? 0) + 1);
        $parkedVehicle->setVehicleId($vehicle->getId());
        $parkedVehicle->setPlateNumber($form->getPlateNumber());
        $parkedVehicle->setEnteredByUserId(Session::auth()['id']);
        $parkedVehicle->setEnteredAt(new DateTime());
        $parkedVehicle->setLeftByUserId(null);
        $parkedVehicle->setLeftAt(null);

        $affected = $this->parkedVehicleRepository->create($parkedVehicle);

        return new ParkedVehicleResource($parkedVehicle);
    }

    /**
     * toggleLeft marks the vehicle by id as left or as stayed
     *
     * @param string $id
     * @return ParkedVehicleResource 
     * @throws \PDOException
     * @throws \App\Exceptions\HttpException
     */
    public function toggleLeft(string $id): ParkedVehicleResource
    {
        $parkedVehicle = $this->parkedVehicleRepository->find($id);
        if (is_null($parkedVehicle)) {
            $this->throwDataNotFoundHttpException();
        }

        $parkedVehicle->setLeftByUserId($parkedVehicle->getLeftByUserId() ? null : Session::auth()['id']);
        $parkedVehicle->setLeftAt($parkedVehicle->getLeftAt() ? null : new DateTime());

        $affected = $this->parkedVehicleRepository->update($parkedVehicle);

        return new ParkedVehicleResource($parkedVehicle);
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
            ->setRedirectionUrlStr('/parked-vehicles');;
    }
}
