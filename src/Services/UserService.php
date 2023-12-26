<?php

namespace App\Services;

use App\Forms\User\LoginForm;
use App\Forms\User\StoreForm;
use App\Forms\User\UpdateForm;
use App\Models\User;
use App\Repositories\UserRepository;
use DateTime;
use Firebase\JWT\JWT;
use Stringy\Stringy;

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
        $token = JWT::encode(
            [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'iat' => time(),
                'exp' => time() + intval($jwtSetting['exp_in']),
            ],
            $jwtSetting['secret'],
            $jwtSetting['algorithm']
        );

        return $token;
    }

    /**
     * paginate 
     *
     * @param int $page
     * @param int $perPage
     * @param ?string $keyword
     * @return array array of users
     * @throws \PDOException
     */
    public function paginate(int $page, int $perPage, ?string $keyword = null): array
    {
        $limit = $perPage;
        $offset = ($page - 1) * $perPage;
        $users = $this->userRepository->paginate($limit, $offset, $keyword);
        return array_map(function (User $user) {
            return $user->toArray();
        }, $users);
    }

    /**
     * find finds by id
     *
     * @param string $id
     * @return array $user
     * @throws \PDOException
     */
    public function find(string $id): array
    {
        $user = $this->userRepository->find($id);
        if (is_null($user)) {
            $this->throwDataNotFoundHttpException();
        }
        return $user->toArray();
    }

    /**
     * store creates a user
     *
     * @param StoreForm $form
     * @return array 
     * @throws \PDOException
     */
    public function store(StoreForm $form): array
    {
        $latestUser = $this->userRepository->findLatest();

        $user = new User();
        $user->setId(($latestUser->getId() ?? 0) + 1);
        $user->setName($form->getName());
        $user->setEmail($form->getEmail());
        $user->setPassword(password_hash($form->getPassword(), PASSWORD_BCRYPT));
        $user->setCreatedAt(new DateTime());
        $user->setUpdatedAt(null);

        $affected = $this->userRepository->create($user);

        return $user->toArray();
    }

    /**
     * update updates by id
     *
     * @param UpdateForm $form
     * @return array 
     * @throws \PDOException
     */
    public function update(UpdateForm $form): array
    {
        $user = $this->userRepository->find($form->getId());
        if (is_null($user)) {
            $this->throwDataNotFoundHttpException();
        }

        $user->setName($form->getName());
        $user->setEmail($form->getEmail());
        if ($form->getPassword() != $user->getPassword()) {
            $user->setPassword(password_hash($form->getPassword(), PASSWORD_BCRYPT));
        }
        $user->setUpdatedAt(new DateTime());

        $affected = $this->userRepository->update($user);

        return $user->toArray();
    }

    /**
     * destroy deletes by id
     *
     * @param string $id
     * @return int affected rows
     * @throws \PDOException
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
