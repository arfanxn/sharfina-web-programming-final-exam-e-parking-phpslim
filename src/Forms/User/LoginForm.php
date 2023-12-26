<?php

namespace App\Forms\User;

class LoginForm extends UserForm
{
    use \App\Traits\FormTrait;

    private string $email;
    private string $password;

    public function getRules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}
