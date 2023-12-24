<?php

namespace App\Requests\User;

use App\Requests\Request;

class LoginRequest extends Request
{
    public function getRules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}
