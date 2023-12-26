<?php

namespace App\Forms\User;

class StoreForm
{
    use \App\Traits\FormTrait;

    private string $id;
    private string $name;
    private string $email;
    private string $password;

    public function getRules(): array
    {
        return [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
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
