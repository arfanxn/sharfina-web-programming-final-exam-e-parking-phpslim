<?php

namespace App\Traits;

use App\Helpers\Arr;

trait HttpableMessageTrait
{
    private string $message;

    public function getMessage(): string
    {
        return $this->message ?? '';
    }
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }
    public function hasMessage(): bool
    {
        return ($this->message ?? '') != '';
    }
}
