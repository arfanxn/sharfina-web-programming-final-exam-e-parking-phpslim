<?php

namespace App\Forms\User;

class StoreForm extends UserForm
{
    use \App\Traits\FormTrait;

    private string $confirmationPassword;

    public function getRules(): array
    {
        return [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required',
            'confirmation_password' => 'required|same:password',
            'deactivated_at' => 'required|date',
        ];
    }

    public function getConfirmationPassword(): string
    {
        return $this->confirmationPassword;
    }
    public function setConfirmationPassword(string $confirmationPassword): self
    {
        $this->confirmationPassword = $confirmationPassword;
        return $this;
    }
}
