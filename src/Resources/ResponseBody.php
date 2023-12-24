<?php

namespace App\Resources;

use App\Helpers\Arr;

class ResponseBody
{
    private int $statusCode; // eg: 422 or 500 or etc...
    private string $statusText; // eg: 'error' or 'success'
    private ?string $message;
    private ?array $payload;

    /**
     * new instantiates instance of ResponseBody
     *
     * @return self
     */
    public static function new(): self
    {
        return new self();
    }

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

    public function getMessage(): string
    {
        return $this->message;
    }
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }
    public function addPayload(string $key, mixed $value): self
    {
        if (!isset($this->payload)) {
            $this->payload = array();
        }
        $this->payload = array_replace($this->payload, Arr::dotToAssoc([$key => $value]));
        return $this;
    }

    public function toArray(): array
    {
        $arr =  [
            'status_code' => $this->statusCode,
            'status_text' => $this->statusText,
        ];

        if (isset($this->message)) {
            $arr = array_merge($arr, ['message' => $this->message]);
        }
        if (isset($this->payload)) {
            $arr = array_merge($arr, $this->payload);
        }

        return $arr;
    }
}
