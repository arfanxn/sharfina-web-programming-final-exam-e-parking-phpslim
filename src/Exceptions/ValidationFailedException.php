<?php

namespace App\Exceptions;

use \App\Exceptions\HttpException;
use Rakit\Validation\ErrorBag;

class ValidationFailedException extends HttpException
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
        $e->setStatusCode(422); // 422 respresents unprocessable entity or invalid inputs or inputs that dont pass validation
        $e->message = $errors->firstOfAll()[key($errors->firstOfAll())];
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
