<?php

namespace App\Traits;

use Psr\Http\Message\ServerRequestInterface as Request;
use Rakit\Validation\Validator;
use Stringy\Stringy;

trait FormTrait
{
    protected array $data; // $data represents the form data
    protected array $rules; // $rules represents the validation rules

    /**
     * newFromRequest instantiates a new form with form data from the request form data
     * 
     * @param Request $request
     * @return self
     */
    public static function newFromRequest(Request $request): self
    {
        $data = array_merge(
            $request->getUploadedFiles(),
            $request->getParsedBody() ?? [],
            $request->getAttributes(),
            $request->getQueryParams(),
        );
        $form = new self();
        $form->hydrate($data);
        return $form;
    }

    /**
     * hydrate fills the form with the given request form data
     *
     * @param array $data form data
     * @return self
     */
    public function hydrate(array $data): self
    {
        foreach ($data as $key => $value) {
            $camelCasedKey = Stringy::create($key)->camelize();
            $setterMethodName = 'set' . ucfirst($camelCasedKey);
            if (method_exists($this, $setterMethodName)) {
                $value = $data[$key];
                $this->$setterMethodName($value);
            } else {
                unset($data[$key]);
            }
        }
        $this->setData($data); // sets the data
        return $this;
    }


    public function getData(): array
    {
        return $this->data ?? [];
    }
    public function setData(array $data): self
    {
        $this->data = [];
        $this->data = $data;
        return $this;
    }

    public function getRules(): array
    {
        return $this->rules ?? [];
    }
    public function setRules(array $rules): self
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Validates
     * 
     * @param bool $silent if true, the error won't be thrown
     * @return \Rakit\Validation\Validation 
     * @throws \App\Exceptions\ValidationFailedException
     */
    public function validate(bool $silent = false): \Rakit\Validation\Validation
    {
        $validator = new Validator();
        $validation = $validator->make(
            $this->getData(),
            $this->getRules(),
        );
        $validation->validate();

        if ($validation->fails() && !$silent) {
            throw \App\Exceptions\ValidationFailedException::newFromErrorBag($validation->errors());
        }

        return $validation;
    }
}
