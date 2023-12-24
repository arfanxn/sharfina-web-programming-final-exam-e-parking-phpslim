<?php

namespace App\Requests;

use Psr\Http\Message\ServerRequestInterface as PSRRequestInterface;
use Rakit\Validation\Validator;

class Request
{
    private PSRRequestInterface $psrRequest;
    private array $rules;

    public function __construct(PSRRequestInterface $psrRequest)
    {
        $this->psrRequest = $psrRequest;
    }

    public function getPSRRequest(): PSRRequestInterface
    {
        return $this->psrRequest;
    }

    public function getRules(): array
    {
        return $this->rules ?? [];
    }
    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    /**
     * Retrieve request body form data.
     *
     * @return array
     */
    public function getFormData(): array
    {
        return array_merge($this->getParsedBody() ?? [], $this->getUploadedFiles());
    }

    /**
     * Retrieve any parameters provided in the request body.
     *
     * @return null|array|object
     */
    public function getParsedBody()
    {
        return $this->psrRequest->getParsedBody();
    }

    /**
     * Retrieve normalized file upload data.
     *
     * @return array
     */
    public function getUploadedFiles(): array
    {
        return $this->psrRequest->getUploadedFiles();
    }

    /**
     *
     * @throws \App\Exceptions\ValidationFailedException
     */
    public function validate()
    {
        $validator = new Validator();
        $validation = $validator->make(
            $this->getFormData(),
            $this->getRules(),
        );
        $validation->validate();

        if ($validation->fails()) {
            throw \App\Exceptions\ValidationFailedException::newFromErrorBag($validation->errors());
        }
    }
}
