<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Firebase\JWT\JWT;

class UserService extends Service
{
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
        $e = \App\Exceptions\ValidationFailedException::newForField('password', 'Invalid credentials');

        $user = $this->userRepository->findByEmail($email);
        // ensure that the user exists
        if (!isset($user)) {
            throw $e;
        }

        // verify password
        if (password_verify($password, $user->getPassword()) == false) {
            throw $e;
        }

        $token = JWT::encode(
            [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'exp' => time() + (3600 * 1 / 2), // expiration time (30 mins)
            ],
            'secret',
            'HS256'
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
