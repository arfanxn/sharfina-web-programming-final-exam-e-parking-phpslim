<?php

namespace App\Controllers;

use App\Forms\PaginationForm;
use App\Forms\ParkedVehicles\EnterForm;
use App\Handlers\ResponseHandler;
use App\Resources\VehicleColorResource;
use App\Resources\VehicleTypeResource;
use App\Services\ParkedVehicleService as ParkedVehicleService;
use App\Services\VehicleColorService;
use App\Services\VehicleTypeService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ParkedVehicleController extends Controller
{
    private ParkedVehicleService $parkedVehicleService;
    private VehicleColorService $vehicleColorService;
    private VehicleTypeService $vehicleTypeService;

    public function __construct(
        ParkedVehicleService $parkedVehicleService,
        VehicleColorService $vehicleColorService,
        VehicleTypeService $vehicleTypeService,
    ) {
        $this->parkedVehicleService = $parkedVehicleService;
        $this->vehicleColorService = $vehicleColorService;
        $this->vehicleTypeService = $vehicleTypeService;
    }

    public function index(Request $request, Response $response): Response
    {
        $form = PaginationForm::newFromRequest($request);
        $form->validate();

        $pagination = $this->parkedVehicleService->paginate($form);

        $doesPaginationHaveData = !empty($pagination->getData());
        $statusCode = $doesPaginationHaveData ? 200 : 404;
        $message = $doesPaginationHaveData ? 'Successfully retrieved parked vehicles.' : 'Data were not found';

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode($statusCode)
            ->setMessage($message)
            ->appendBody('pagination', $pagination->toArray())
            ->render('parked-vehicles/index.phtml');
    }

    public function view(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $resource = $this->parkedVehicleService->find($id);

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(200)
            ->setMessage('Successfully retrieved parked vehicle.')
            ->appendBody('parked_vehicle', $resource->toArray())
            ->render('parked-vehicles/view.phtml');
    }

    public function enter(Request $request, Response $response): Response
    {
        $vehicleColors = array_map(function (VehicleColorResource $resource) {
            return $resource->toArray();
        }, $this->vehicleColorService->all());
        $vehicleTypes = array_map(function (VehicleTypeResource $resource) {
            return $resource->toArray();
        }, $this->vehicleTypeService->all());

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(200)
            ->mergeBody([
                'vehicle_colors' => $vehicleColors,
                'vehicle_types' => $vehicleTypes,
            ])
            ->render('parked-vehicles/enter.phtml');
    }

    public function handleEnter(Request $request, Response $response): Response
    {
        $form = EnterForm::newFromRequest($request);
        $form->validate();

        $resource = $this->parkedVehicleService->enter($form);

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(201)
            ->setMessage('Successfully entered parked vehicle.')
            ->appendBody('parked_vehicle', $resource->toArray())
            ->redirect($_SERVER['HTTP_REFERER'] ?? '/parked-vehicles');
    }

    public function toggleLeft(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $resource = $this->parkedVehicleService->toggleLeft($id);
        $resourceArray = $resource->toArray();
        $message = 'Successfully toggled parked vehicle with plate number '
            . "\"" . $resourceArray['plate_number'] . "\""
            . ' as '
            . (in_array($resourceArray['left_at'], ['', null]) ? 'stayed' : 'left')
            . '.';

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(200)
            ->setMessage($message)
            ->appendBody('parked_vehicle', $resourceArray)
            ->redirect($_SERVER['HTTP_REFERER'] ?? '/parked-vehicles');
    }
}
