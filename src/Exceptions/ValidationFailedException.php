<?php

namespace App\Exceptions;

use Rakit\Validation\ErrorBag;

class ValidationFailedException extends \Exception
{
    private ErrorBag $errors;

    public function getErrors(): ErrorBag
    {
        return $this->errors;
    }

    public function setErrors(ErrorBag $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * newFromErrorBag instantiates a new ValidationFailedException instance with the given ErrorBag instance
     *
     * @param ErrorBag $errors
     * @return self
     */
    public static function newFromErrorBag(ErrorBag $errors): self
    {
        $e = new self($errors->firstOfAll()[key($errors->firstOfAll())]);
        $e->setErrors($errors);
        return $e;
    }

    /**
     * newForField instantiates a new ValidationFailedException for a single field
     *
     * @param string $field  name of the field
     * @param string $message error message of the field
     * @return self
     */
    public static function newForField(string $field, string $message): self
    {
        $errors = new ErrorBag();
        $errors->add(
            $field,
            '',
            str_replace([':attribute', ':field'], $field, $message),
        );
        return self::newFromErrorBag($errors);
    }

}
