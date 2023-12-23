<?php

namespace App\Controllers;

use App\Requests\User\LoginRequest;
use App\Resources\ResponseBody;
use App\Services\UserService as UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(Request $request, Response $response): Response
    {
        $request = new LoginRequest($request);
        $request->validate();

        $token = $this->userService->login($request->email, $request->password);

        return $response->withJson(
            ResponseBody::instantiate()
                ->setStatusAsSuccess()
                ->setMessage('Login successful')
                ->addPayload('token', $token)
                ->toArray(),
            200,
        );
    }

    public function view(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $user = $this->userService->findById($id);

        return $response->withJson(
            ResponseBody::instantiate()
                ->setStatusAsSuccess()
                ->setMessage('Retrieved successfully')
                ->addPayload('user', $user->toArray())
                ->toArray(),
            200,
        );
    }
}
