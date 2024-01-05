<?php

namespace App\Controllers;

use App\Forms\PaginationForm;
use App\Forms\User\LoginForm;
use App\Forms\User\StoreForm;
use App\Forms\User\UpdateForm;
use App\Handlers\ResponseHandler;
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
        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(200)
            ->render("users/login.phtml");
    }

    public function handleLogin(Request $request, Response $response): Response
    {
        $form = LoginForm::newFromRequest($request);
        $form->validate();

        $jwtSetting = $this->getContainer()->get('settings')['jwt'];
        $token = $this->userService->login($form);

        setcookie(
            'Authorization',
            'Bearer ' . $token,
            time() + intval($jwtSetting['exp_in']),
            '/',
            $request->getUri()->getHost(),
            filter_var($jwtSetting['secure'], FILTER_VALIDATE_BOOLEAN),
            true,
        );

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(200)
            ->setMessage('Login successfully.')
            ->redirect('/');
    }

    public function handleLogout(Request $request, Response $response): Response
    {
        setcookie('Authorization', '', time() - 3600, "/"); // remove authorization cookie
        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(200)
            ->setMessage('Logout successfully.')
            ->redirect('/users/login');
    }

    public function index(Request $request, Response $response): Response
    {
        $form = PaginationForm::newFromRequest($request);
        $form->validate();

        $pagination = $this->userService->paginate($form);

        $doesPaginationHaveData = !empty($pagination->getData());
        $statusCode = $doesPaginationHaveData ? 200 : 404;
        $message = $doesPaginationHaveData ? 'Successfully retrieved users.' : 'Data were not found.';

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode($statusCode)
            ->setMessage($message)
            ->appendBody('pagination', $pagination->toArray())
            ->render('users/index.phtml');
    }

    public function view(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $resource = $this->userService->find($id);

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(200)
            ->setMessage('Successfully retrieved user.')
            ->appendBody('user', $resource->toArray())
            ->render('users/view.phtml');
    }

    public function create(Request $request, Response $response): Response
    {
        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(200)
            ->render('users/create.phtml');
    }

    public function store(Request $request, Response $response): Response
    {
        $form = StoreForm::newFromRequest($request);
        $form->validate();

        $resource = $this->userService->store($form);

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(201)
            ->setMessage('Successfully created user.')
            ->appendBody('user', $resource->toArray())
            ->redirect($_SERVER['HTTP_REFERER'] ?? '/users');
    }

    public function edit(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $resource = $this->userService->find($id);

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(200)
            ->appendBody('user', $resource->toArray())
            ->render('users/edit.phtml');
    }

    public function update(Request $request, Response $response): Response
    {
        $form = UpdateForm::newFromRequest($request);
        $form->validate();

        $resource = $this->userService->update($form);

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(200)
            ->setMessage('Successfully updated user.')
            ->appendBody('user', $resource->toArray())
            ->redirect($_SERVER['HTTP_REFERER'] ?? '/users');
    }

    public function destroy(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $affected = $this->userService->destroy($id);

        return ResponseHandler::new($this->getContainer())
            ->setResponse($response)
            ->setStatusCode(200)
            ->setMessage('Successfully deleted user.')
            ->redirect($_SERVER['HTTP_REFERER'] ?? '/users');
    }
}
