<?php

namespace App\Requests;

use Psr\Http\Message\ServerRequestInterface as PSRRequestInterface;

class Request
{
    protected PSRRequestInterface $psrRequest;

    public function __construct(PSRRequestInterface $psrRequest)
    {
        $this->psrRequest = $psrRequest;
    }

}
