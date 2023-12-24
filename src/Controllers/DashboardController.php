<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, Response $response): Response
    {
        return $this->getContainer()->renderer->render($response, 'dashboard.phtml');
    }
}
