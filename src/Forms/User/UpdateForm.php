<?php

namespace App\Forms\User;

class UpdateForm extends UserForm
{
    use \App\Traits\FormTrait;

    public function getRules(): array
    {
        return [
            'id' => 'required',
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required',
            'deactivated_at' => 'required|date',
        ];
    }
}
