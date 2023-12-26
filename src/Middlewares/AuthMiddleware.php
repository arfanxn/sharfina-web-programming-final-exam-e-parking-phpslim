<?php

namespace App\Middlewares;

use App\Handlers\ResponseHandler;
use App\Helpers\Session;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Resources\ResponseBody;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Stringy\Stringy;

/**
 *  AuthMiddleware  
 */
class AuthMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $jwtSetting = $this->getContainer()->get('settings')['jwt'];

            $cookieValue = $_COOKIE['Authorization'] ?? null;
            if (is_null($cookieValue)) {
                throw new \App\Exceptions\UnauthorizedException();
            }

            $token = (new Stringy($cookieValue))->after('Bearer ')->toString();

            $payload = JWT::decode($token, new Key($jwtSetting['secret'], $jwtSetting['algorithm']));

            $request->withAttribute('auth', $payload);

            $response = $next($request, $response);
            return $response;
        } catch (\App\Exceptions\UnauthorizedException $e) {
            return ResponseHandler::new($this->getContainer())
                ->setResponse($response)
                ->setStatusCode(401)->setMessage('Unauthorized action, please login.')
                ->redirect('/users/login');
        }
    }
}
