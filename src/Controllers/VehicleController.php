<?php

namespace App\Controllers;

use App\Forms\PaginationForm;
use App\Handlers\ResponseHandler;
use App\Services\VehicleService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class VehicleController extends Controller
{
    private VehicleService $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    public function index(Request $request, Response $response): Response
    {
        $form = PaginationForm::newFromRequest($request);
        $form->validate();

        $pagination = $this->vehicleService->paginate($form);

        $doesPaginationHaveData = !empty($pagination->getData());
        $statusCode = $doesPaginationHaveData ? 200 : 404;
        $message = $doesPaginationHaveData ? 'Successfully retrieved vehicles.' : 'Data were not found.';

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode($statusCode)
            ->setMessage($message)
            ->appendBody('pagination', $pagination->toArray())
            ->render('vehicles/index.phtml');
    }

    public function view(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $resource = $this->vehicleService->find($id);

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(200)
            ->setMessage('Successfully retrieved vehicle.')
            ->appendBody('vehicle', $resource->toArray())
            ->render('vehicles/view.phtml');
    }
}
