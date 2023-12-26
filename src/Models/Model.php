<?php

namespace App\Models;

use App\Interfaces\ArrayableInterface;
use Stringy\Stringy;

class Model implements ArrayableInterface
{
    /**
     * this represents the table columns
     */
    protected array $columns;

    /**
     * this represents the columns that will be hidden when converted to an array
     */
    protected array $hiddenColumns;

    /**
     * hydrate fills the model with the given data
     *
     * @param array $data
     * @return self
     */
    public function hydrate(array $data): self
    {
        foreach ($this->columns as $column) {
            $camelCasedColumn = Stringy::create($column)->camelize();
            $setterMethodName = 'set' . ucfirst($camelCasedColumn);
            if (method_exists($this, $setterMethodName)) {
                $value = $data[$column];
                $this->$setterMethodName($value);
            }
        }
        return $this;
    }


    /**
     * toArray converts self (model) to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [];
        foreach ($this->columns as $column) {
            if (isset($this->hiddenColumns) && in_array($column, $this->hiddenColumns)) {
                continue; // skip hidden columns
            }

            $camelCasedColumn = Stringy::create($column)->camelize();
            $getterMethodName = 'get' . ucfirst($camelCasedColumn);

            if (method_exists($this, $getterMethodName)) {
                $data[$column] =  $this->$getterMethodName();
            }
        }
        return $data;
    }
}
