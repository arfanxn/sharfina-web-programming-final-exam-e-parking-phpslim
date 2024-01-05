<?php

namespace App\Forms\Users;

class StoreForm extends UserForm
{
    use \App\Traits\FormTrait;

    public function getRules(): array
    {
        return [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'deactived_at' => 'nullable|date:Y-m-d',
        ];
    }
}
