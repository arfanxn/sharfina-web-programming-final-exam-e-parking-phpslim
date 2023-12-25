<?php

namespace App\Middlewares;

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
class AuthMiddleware
{
    use \App\Traits\ContainerAwareTrait;

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
        } catch (\App\Exceptions\UnauthorizedException $e) {
            $data = ResponseBody::new()
                ->setStatusCode(401)
                ->setMessage('Unauthorized action, please login.')
                ->toArray();
            Session::putRedirectData($data);

            $response = $response->withHeader('Location', '/users/login');
        } finally {
            return $response;
        }
    }
}
