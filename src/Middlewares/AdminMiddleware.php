<?php

namespace App\Middlewares;

use App\Exceptions\HttpException;
use App\Handlers\ResponseHandler;
use App\Helpers\Session;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Stringy\Stringy;

/**
 *  AdminMiddleware  
 */
class AdminMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $e = HttpException::new()
                ->setStatusCode(403)
                ->__setMessage('You don\'t have permission to access this resource.')
                ->setRedirectionUrlStr('/');

            $auth = Session::auth();
            if (is_null($auth) || !Stringy::create($auth['email'])->contains('admin')) {
                throw $e;
            }

            $response = $next($request, $response);
            return $response;
        } catch (\App\Exceptions\HttpException $e) {
            if ($e->getStatusCode() != 403) {
                throw $e; // rethrow exception if code is not 403 (Forbidden)
            }
            // Redirect to the dashboard
            return ResponseHandler::new($this->getContainer())
                ->setResponse($response)
                ->setStatusCode($e->getStatusCode())->setMessage($e->getMessage())
                ->redirect($e->getRedirectionUrlStr());
        }
    }
}
