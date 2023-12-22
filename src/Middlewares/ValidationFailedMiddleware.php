<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ValidationFailedMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $response = $next($request, $response);
        } catch (\App\Exceptions\ValidationFailedException $e) {
            $response = $response->withJson([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }

        return $response;
    }
}
