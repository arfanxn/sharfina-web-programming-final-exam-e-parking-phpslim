<?php

namespace App\Resources;

use App\Interfaces\ArrayableInterface;
use App\Models\User;

class UserResource implements ArrayableInterface
{
    private User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function toArray(): array
    {
        $model = $this->model;
        return [
            'id' => $model->getId(),
            'name' => $model->getName(),
            'email' => $model->getEmail(),
            'created_at' => $model->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $model->getUpdatedAt() ? $model->getUpdatedAt()->format('Y-m-d H:i:s') : null,
            'deactived_at' => $model->getDeactivedAt() ? $model->getDeactivedAt()->format('Y-m-d H:i:s') : null,
        ];
    }
}
