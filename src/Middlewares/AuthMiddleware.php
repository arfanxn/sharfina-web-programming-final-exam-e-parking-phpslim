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

            $cookieValue = $_COOKIE['Authorization'];
            if (!isset($cookieValue)) {
                throw new \App\Exceptions\UnauthorizedException();
            }

            $token = (new Stringy($cookieValue))->after('Bearer ')->toString();

            $payload = JWT::decode($token, new Key($jwtSetting['secret'], $jwtSetting['algorithm']));

            $request->withAttribute('auth', $payload);

            $response = $next($request, $response);
        } catch (\Exception | \App\Exceptions\UnauthorizedException $e) {
            Session::putRedirectData(
                ResponseBody::instantiate()
                    ->setStatusAsError()
                    ->setMessage('Unauthorized action, please login.')
                    ->toArray()
            );

            $response = $response->withRedirect('/users/login');
        } finally {
            return $response;
        }
    }
}
