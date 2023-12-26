<?php

namespace App\Middlewares;

use App\Handlers\ResponseHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ValidationFailedMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $response = $next($request, $response);
            return $response;
        } catch (\App\Exceptions\ValidationFailedException $e) {
            $previousUrl = $_SERVER['HTTP_REFERER'] ?? '/';

            return ResponseHandler::new($this->getContainer())
                ->setResponse($response)
                ->setStatusCode($e->getStatusCode() ?? 422)
                ->setMessage($e->getMessage())
                ->appendBody('errors', $e->getErrors()->firstOfAll())
                ->redirect($previousUrl);
        }
    }
}
