<?php

namespace App\Controllers;

use App\Handlers\ResponseHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, Response $response): Response
    {
        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(200)
            ->render('dashboard.phtml');
    }
}
