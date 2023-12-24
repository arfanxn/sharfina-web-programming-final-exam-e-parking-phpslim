<?php

namespace App\Requests\User;

use Psr\Http\Message\ServerRequestInterface as PSRRequestInterface;
use App\Requests\Request;

class LoginRequest extends Request
{
    public function __construct(PSRRequestInterface $psrRequest)
    {
        parent::__construct($psrRequest);

        $this->setRules([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }
}
