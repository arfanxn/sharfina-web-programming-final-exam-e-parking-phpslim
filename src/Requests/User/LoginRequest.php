<?php

namespace App\Requests\User;

use Psr\Http\Message\ServerRequestInterface as PSRRequestInterface;
use App\Requests\Request;

class LoginRequest extends Request
{
    public string $email;
    public string $password;

    public function __construct(PSRRequestInterface $psrRequest)
    {
        parent::__construct($psrRequest);

        $this->email = $this->getFormData()['email'] ?? '';
        $this->password  = $this->getFormData()['password'] ?? '';
        $this->setRules([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }
}
