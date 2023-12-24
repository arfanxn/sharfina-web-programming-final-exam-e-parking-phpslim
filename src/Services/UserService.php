<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
     * @param string $email
     * @param string $password
     * @return string
     * @throws \App\Exceptions\ValidationFailedException
     */
    public function login($email, $password): string
    {
        $e = \App\Exceptions\ValidationFailedException::newForField('password', 'These credentials do not match our records.');

        $user = $this->userRepository->findByEmail($email);
        // ensure that the user exists
        if (!isset($user)) {
            throw $e;
        }

        // verify password
        if (password_verify($password, $user->getPassword()) == false) {
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
     * finds by id
     *
     * @param string $id
     * @return ?User $user
     */
    public function findById(string $id): ?User
    {
        $user = $this->userRepository->find($id);
        return $user;
    }
}
