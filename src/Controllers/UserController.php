<?php

namespace App\Controllers;

use App\Forms\User\LoginForm;
use App\Forms\User\UpdateForm;
use App\Helpers\Session;
use App\Resources\Pagination;
use App\Resources\ResponseBody;
use App\Services\UserService as UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Stringy\Stringy;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(Request $request, Response $response): Response
    {
        return $this->getContainer()->renderer->render(
            $response,
            'users/login.phtml',
            ResponseBody::new()
                ->setStatusCode(200)
                ->mergePayload(Session::pullRedirectData())
                ->toArray()
        );
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
        Session::putRedirectData(ResponseBody::new()
            ->setStatusCode(200)
            ->setMessage('Login successfully.')
            ->toArray());
        return $response->withHeader('Location', '/');
    }

    public function handleLogout(Request $request, Response $response): Response
    {
        setcookie('Authorization', '', time() - 3600, "/"); // remove authorization cookie
        Session::putRedirectData(ResponseBody::new()
            ->setStatusCode(200)
            ->setMessage('Logout successfully.')
            ->toArray());
        return $response->withHeader('Location', '/users/login');
    }

    public function index(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $page = intval($params['page'] ?? 1);
        $perPage = intval($params['per_page'] ?? 10);
        $keyword = $params['keyword'] ?? null;

        $users = $this->userService->paginate($page, $perPage, $keyword);
        $pagination = Pagination::new()->fillMetadata($page, $perPage)->setData($users)->toArray();

        return $this->getContainer()->renderer->render(
            $response,
            'users/index.phtml',
            ResponseBody::new()
                ->setStatusCode(200)
                ->setMessage('Successfully retrieved users.')
                ->mergePayload(Session::pullRedirectData())
                ->addPayload('pagination', $pagination)
                ->toArray()
        );
    }

    public function view(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $user = $this->userService->find($id);

        return $this->getContainer()->renderer->render(
            $response,
            'users/view.phtml',
            ResponseBody::new()
                ->setStatusCode(200)
                ->setMessage('Successfully retrieved user.')
                ->addPayload('user', $user)
                ->toArray()
        );
    }

    public function edit(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $user = $this->userService->find($id);;

        $data = ResponseBody::new()
            ->mergePayload(Session::pullRedirectData())
            ->addPayload('user', $user)
            ->toArray();
        return $this->getContainer()->renderer->render(
            $response,
            'users/edit.phtml',
            $data
        );
    }

    public function update(Request $request, Response $response): Response
    {
        $form = UpdateForm::newFromRequest($request);
        $form->validate();

        $user = $this->userService->update($form);

        Session::putRedirectData(ResponseBody::new()
            ->setStatusCode(200)
            ->setMessage('Successfully updated user.')
            ->addPayload('user', $user)
            ->toArray());
        return $response->withHeader(
            'Location',
            Stringy::create('')->append('/users/', $form->getId(), '/edit')->toString()
        );
    }

    public function destroy(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $affected = $this->userService->destroy($id);

        Session::putRedirectData(ResponseBody::new()
            ->setStatusCode(200)
            ->setMessage('Successfully deleted user.')
            ->toArray());
        return $response->withHeader('Location', '/users');
    }
}
