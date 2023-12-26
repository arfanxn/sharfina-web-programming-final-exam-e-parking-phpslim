<?php

namespace App\Middlewares;

use App\Handlers\ResponseHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Resources\ResponseBody;

/**
 *  ErrorMiddleware catches unhandled exceptions then returns internal server error status
 */
class ErrorMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $response = $next($request, $response);
            return $response;
        } catch (\Exception $e) {
            $statusCode = 500; // represents http status code
            return ResponseHandler::new($this->getContainer())
                ->setResponse($response)
                ->setStatusCode($statusCode)
                ->setMessage($e->getMessage())
                ->json();
        }
    }
}
