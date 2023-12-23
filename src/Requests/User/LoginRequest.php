<?php

namespace App\Requests\User;

use App\Requests\Request;
use Rakit\Validation\Validator;

class LoginRequest extends Request
{
    /**
     *
     * @throws \App\Exceptions\ValidationFailedException
     */
    public function validate()
    {
        $inputs = $this->psrRequest->getParsedBody() ?? [];

        $validator = new Validator();
        $validation = $validator->make($inputs, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $validation->validate();

        if ($validation->fails()) {
            throw \App\Exceptions\ValidationFailedException::newFromErrorBag($validation->errors());
        }
    }

}
