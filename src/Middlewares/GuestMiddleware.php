<?php

namespace App\Middlewares;

use App\Handlers\ResponseHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Stringy\Stringy;

/**
 *  GuestMiddleware verifies that the user is not logged in aka guest, if the verified user is logged in then redirect to dashboard page
 */
class GuestMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            $jwtSetting = $this->getContainer()->get('settings')['jwt'];

            $cookieValue = $_COOKIE['Authorization'] ?? null;
            if (is_null($cookieValue)) {
                return $next($request, $response);
            }

            $token = (new Stringy($cookieValue))->after('Bearer ')->toString();

            JWT::decode($token, new Key($jwtSetting['secret'], $jwtSetting['algorithm']));

            // Redirect to the dashboard page if user is logged in
            return ResponseHandler::new($this->getContainer())
                ->setResponse($response)
                ->setStatusCode(200)
                ->redirect('/');
        } catch (
            \InvalidArgumentException |
            \DomainException |
            \UnexpectedValueException |
            \Firebase\JWT\SignatureInvalidException |
            \Firebase\JWT\BeforeValidException |
            \Firebase\JWT\ExpiredException  $e
        ) {
            return $next($request, $response);
        }
    }
}
