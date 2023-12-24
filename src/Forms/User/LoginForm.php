<?php

namespace App\Forms\User;

class LoginForm
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

    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
}
