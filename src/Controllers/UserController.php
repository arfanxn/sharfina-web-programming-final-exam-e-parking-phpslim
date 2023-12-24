<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Requests\User\LoginRequest;
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
        return $this->getContainer()->renderer->render($response, 'users/login.phtml', Session::pullRedirectData());
    }

    public function handleLogin(Request $request, Response $response): Response
    {
        $request = new LoginRequest($request);
        $request->validate();
        $requestForm = $request->getFormData();

        $jwtSetting = $this->getContainer()->get('settings')['jwt'];
        $token = $this->userService->login($requestForm['email'], $requestForm['password']);

        setcookie(
            'Authorization',
            'Bearer ' . $token,
            time() + intval($jwtSetting['exp_in']),
            '/',
            $request->getPSRRequest()->getUri()->getHost(),
            filter_var($jwtSetting['secure'], FILTER_VALIDATE_BOOLEAN),
            true,
        );
        return $response->withHeader('Location', '/users/1');
    }

    public function view(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $user = $this->userService->findById($id);

        var_dump($user);

        return $response;
    }
}
