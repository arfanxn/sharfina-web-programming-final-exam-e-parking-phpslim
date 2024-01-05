<?php

namespace App\Models;

use App\Interfaces\ArrayableInterface;
use Stringy\Stringy;

class Model implements ArrayableInterface
{
    /**
     * this represents the table name
     */
    protected string $table;

    public function getTable(): string
    {
        return $this->table;
    }
    protected function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * this represents the table columns
     */
    protected array $columns;

    public function getColumns(): array
    {
        return $this->columns ?? [];
    }
    protected function setColumns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

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
        $arr = [];
        foreach ($this->getColumns() as $column) {
            $camelCasedColumn = Stringy::create($column)->camelize();
            $getterMethodName = 'get' . ucfirst($camelCasedColumn);
            if (method_exists($this, $getterMethodName)) {
                $arr[$column] =  $this->$getterMethodName();
            }
        }
        return $arr;
    }
}
