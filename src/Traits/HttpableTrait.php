<?php

namespace App\Traits;

use App\Helpers\Arr;

trait HttpableTrait
{
    private int $statusCode; // eg: 422 or 500 or etc...
    private string $statusText; // eg: 'error' or 'success'
    private string $message;
    private array $body;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode  = $statusCode;
        $this->statusText = $statusCode >= 200 && $statusCode <= 299 ? 'success' : 'error';
        return $this;
    }

    public function getStatusText(): string
    {
        return $this->statusText;
    }
    public function setStatusText(string $statusText): self
    {
        $this->statusText = $statusText;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message ?? '';
    }
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }


    public function getBody(): ?array
    {
        if (!isset($this->statusCode)) {
            $this->setStatusCode(200); // if status code is not set then sets status code to 200
        }

        $body =  [
            'status_code' => $this->getStatusCode(),
            'status_text' => $this->getStatusText(),
        ];

        if ($this->getMessage() != '') {
            $body = array_merge($body, ['message' => $this->getMessage()]);
        }
        if (isset($this->body) && !empty($this->body)) {
            $body = array_merge($body, $this->body);
        }

        $this->body = $body;

        return $this->body;
    }
    public function setBody(array $body): self
    {
        $this->body = $body;
        return $this;
    }
    public function mergeBody(array $body): self
    {
        $this->body = array_replace($this->getBody(), $body);
        return $this;
    }
    public function appendBody(string $key, mixed $value): self
    {
        if (!isset($this->body)) {
            $this->body = array();
        }
        $this->body = array_replace($this->getBody(), Arr::dotToAssoc([$key => $value]));
        return $this;
    }
}
