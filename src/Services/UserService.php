<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService extends Service
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        $this->userRepository->find(1);
    }

}
