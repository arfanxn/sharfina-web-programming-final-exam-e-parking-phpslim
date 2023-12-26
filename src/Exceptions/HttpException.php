<?php

namespace App\Exceptions;

class HttpException extends \Exception
{
    use \App\Traits\HttpableTrait;

    private string $redirectionUrlStr;

    public  function __construct()
    {
    }
    public static function new(): self
    {
        return new self();
    }

    public function getRedirectionUrlStr(): string
    {
        $this->redirectionUrlStr = $this->redirectionUrlStr ?? $_SERVER['HTTP_REFERER'];
        return  $this->redirectionUrlStr;
    }
    public function setRedirectionUrlStr(string $redirectionUrlStr): self
    {
        $this->redirectionUrlStr = $redirectionUrlStr;
        return $this;
    }
}
