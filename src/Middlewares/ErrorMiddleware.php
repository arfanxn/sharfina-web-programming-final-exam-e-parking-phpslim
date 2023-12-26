<?php

namespace App\Middlewares;

use App\Handlers\ResponseHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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
        } catch (\App\Exceptions\HttpException $e) {
            $rh = ResponseHandler::new($this->getContainer())
                ->setResponse($response)
                ->setStatusCode($e->getStatusCode() ?? 500);

            if ($e->hasMessage()) {
                $rh->setMessage($e->getMessage());
            }

            return $rh->redirect($e->getRedirectionUrlStr());
        }
    }
}
