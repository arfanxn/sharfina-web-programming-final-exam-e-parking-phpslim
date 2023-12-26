<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Resources\ResponseBody;

/**
 *  ErrorMiddleware catches unhandled exceptions then returns internal server error status
 */
class ErrorMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $response = $next($request, $response);
            return $response;
        } catch (\Exception $e) {
            $statusCode = 500; // represents http status code
            $response = $response->withJson(
                ResponseBody::new()
                    ->setStatusCode($statusCode)
                    ->setMessage($e->getMessage())
                    ->toArray(),
                $statusCode
            );
            return $response;
        }
    }
}
