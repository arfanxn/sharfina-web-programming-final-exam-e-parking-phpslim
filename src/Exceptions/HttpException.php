<?php

namespace App\Exceptions;

class HttpException extends \Exception
{
    use \App\Traits\HttpableTrait;

    private ?string $redirectionUrlStr;

    public  function __construct()
    {
    }
    public static function new(): self
    {
        return new self();
    }

    public function getRedirectionUrlStr(): ?string
    {
        $this->redirectionUrlStr = $this->redirectionUrlStr ?? $_SERVER['HTTP_REFERER'] ?? null;
        return  $this->redirectionUrlStr;
    }
    public function setRedirectionUrlStr(?string $redirectionUrlStr): self
    {
        $this->redirectionUrlStr = $redirectionUrlStr;
        return $this;
    }

    /**
     * __setMessage is used to set the message variable of this class/object. Why is the method named with __ is because the method itself overrides the Exception's final getMessage method.
     * 
     * @param string $message
     * @return self
     */
    public function __setMessage($message): self
    {
        $this->message = $message;
        return $this;
    }
}
