<?php

namespace App\Middlewares;

use App\Helpers\Session;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Resources\ResponseBody;

class ValidationFailedMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $response = $next($request, $response);
            return $response;
        } catch (\App\Exceptions\ValidationFailedException $e) {
            $previousUrl = $_SERVER['HTTP_REFERER'] ?? '/';
            Session::putRedirectData(
                ResponseBody::new()
                    ->setStatusCode(422)
                    ->setMessage($e->getMessage())
                    ->addPayload('errors', $e->getErrors()->firstOfAll())
                    ->toArray()
            );

            $response = $response->withHeader('Location', $previousUrl);
            return $response;
        }
    }
}
