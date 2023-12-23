<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Resources\ResponseBody;

class ValidationFailedMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $response = $next($request, $response);
        } catch (\App\Exceptions\ValidationFailedException $e) {
            $response = $response->withJson(
                ResponseBody::instantiate()
                    ->setStatusAsError()
                    ->setMessage($e->getMessage())
                    ->addPayload('errors', $e->getErrors()->firstOfAll())
                    ->toArray(),
                $statusCode = 422
            );
        }

        return $response;
    }
}
