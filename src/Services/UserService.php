<?php

namespace App\Services;

use App\Forms\PaginationForm;
use App\Forms\Users\LoginForm;
use App\Forms\Users\StoreForm;
use App\Forms\Users\UpdateForm;
use App\Helpers\Session;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Resources\Pagination;
use DateTime;
use Firebase\JWT\JWT;
use App\Resources\UserResource;

class UserService extends Service
{
    use \App\Traits\ContainerAwareTrait;

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Login does login operations and returns the jwt token if successful or throws an exception otherwise
     *
     * @param LoginForm $form
     * @return string
     * @throws \App\Exceptions\ValidationFailedException
     * @throws \PDOException
     */
    public function login(LoginForm $form): string
    {
        $e = \App\Exceptions\ValidationFailedException::newForField('password', 'These credentials do not match our records.');

        $user = $this->userRepository->findByEmail($form->getEmail());
        // ensure that the user exists
        if (!isset($user)) {
            throw $e;
        }

        // verify password
        if (password_verify($form->getPassword(), $user->getPassword()) == false) {
            throw $e;
        }

        $jwtSetting = $this->getContainer()->get('settings')['jwt'];
        $auth = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'iat' => time(),
            'exp' => time() + intval($jwtSetting['exp_in']),
        ];
        $token = JWT::encode($auth, $jwtSetting['secret'], $jwtSetting['algorithm']);
        Session::auth($auth);

        return $token;
    }

    /**
     * paginate 
     *
     * @param PaginationForm $form
     * @return Pagination
     * @throws \PDOException
     */
    public function paginate(PaginationForm $form): Pagination
    {
        $users = $this->userRepository->paginate($form->getLimit(), $form->getOffset(), $form->getKeyword());
        $resources = array_map(function (User $user) {
            return new UserResource($user);
        }, $users);
        return Pagination::new()->fillMetadata($form->getPage(), $form->getPerPage(), $form->getKeyword())
            ->setData($resources);
    }

    /**
     * find finds by id
     *
     * @param string $id
     * @return UserResource
     * @throws \PDOException
     * @throws \App\Exceptions\HttpException
     */
    public function find(string $id): UserResource
    {
        $user = $this->userRepository->find($id);
        if (is_null($user)) {
            $this->throwDataNotFoundHttpException();
        }
        return new UserResource($user);
    }

    /**
     * store creates a user
     *
     * @param StoreForm $form
     * @return UserResource
     * @throws \PDOException
     */
    public function store(StoreForm $form): UserResource
    {
        $latestUser = $this->userRepository->findLatest();

        $user = new User();
        $user->setId(($latestUser->getId() ?? 0) + 1);
        $user->setName($form->getName());
        $user->setEmail($form->getEmail());
        $user->setPassword(password_hash($form->getPassword(), PASSWORD_BCRYPT));
        $user->setCreatedAt(new DateTime());
        $user->setUpdatedAt(null);
        $user->setDeactivedAt($form->getDeactivedAt());

        $affected = $this->userRepository->create($user);

        return new UserResource($user);
    }

    /**
     * update updates by id
     *
     * @param UpdateForm $form
     * @return UserResource 
     * @throws \PDOException
     * @throws \App\Exceptions\HttpException
     */
    public function update(UpdateForm $form): UserResource
    {
        $user = $this->userRepository->find($form->getId());
        if (is_null($user)) {
            $this->throwDataNotFoundHttpException();
        }

        $user->setName($form->getName());
        $user->setEmail($form->getEmail());
        $user->setPassword($form->getPassword() != null ?
            password_hash($form->getPassword(), PASSWORD_BCRYPT) : $user->getPassword());
        $user->setUpdatedAt(new DateTime());
        $user->setDeactivedAt($form->getDeactivedAt());

        $affected = $this->userRepository->update($user);

        return new UserResource($user);
    }

    /**
     * destroy deletes by id
     *
     * @param string $id
     * @return int affected rows
     * @throws \PDOException
     * @throws \App\Exceptions\HttpException
     */
    public function destroy(string $id): int
    {
        $user = $this->userRepository->find($id);
        if (is_null($user)) {
            $this->throwDataNotFoundHttpException();
        }
        $affected = $this->userRepository->delete($id);
        return $affected;
    }

    /**
     * ------------------------------------------------------------------------------------------------
     * Class utility methods
     * ------------------------------------------------------------------------------------------------
     */

    /**
     * throwDataNotFoundHttpException
     *
     * @return never
     * @throws \App\Exceptions\HttpException
     */
    public function throwDataNotFoundHttpException()
    {
        throw \App\Exceptions\HttpException::new()
            ->setStatusCode(404)
            ->__setMessage('Data was not found.')
            ->setRedirectionUrlStr('/users');;
    }
}
