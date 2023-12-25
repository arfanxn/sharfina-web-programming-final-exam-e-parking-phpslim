<?php

namespace App\Exceptions;

use Stringy\Stringy;

class ModelNotFoundException extends \Exception
{
    public static function new(string $model, string|array $id): self
    {
        $key = 'id';
        $value = $id;
        if (is_array($id)) {
            $key = array_keys($id)[0];
            $value = $id[$key];
        }
        if (Stringy::create($model)->contains('\\')) {
            $model = Stringy::create($model)->afterLast('\\')->toString();
        }

        $e = new self(Stringy::create()->append(ucfirst($model), ' with ', $key, ' ', $value, ' was not found.'));
        return $e;
    }
}
