<?php

use App\Helpers\Arr;

class ResponseBody
{
    private string $status; // eg: 'error' or 'success'
    private string $message;
    private array $payload;

    /**
     * instantiate instance of Response
     *
     * @return self
     */
    public static function instantiate(): self
    {
        return new self();
    }

    public function getStatus(): string
    {
        return $this->status;
    }
    public function setStatusAsError(): self
    {
        $this->status = 'error';
        return $this;
    }
    public function setStatusAsSuccess(): self
    {
        $this->status = 'success';
        return $this;
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

    public function getPayload(): array
    {
        return $this->payload;
    }
    public function addPayload(string $key, mixed $value): self
    {
        $this->payload = array_replace($this->payload, Arr::dotToAssoc([$key => $value])) ;
        return $this;
    }

    public function toArray(): array
    {
        $arr =  [
            'status' => $this->status,
            'message' => $this->message,
        ];

        if (isset($this->payload)) {
            $arr = array_merge($arr, $this->payload);
        }

        return $arr;
    }

}
