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
            'deactived_at' => 'nullable|date:Y-m-d H:i:s',
        ];
    }
}
