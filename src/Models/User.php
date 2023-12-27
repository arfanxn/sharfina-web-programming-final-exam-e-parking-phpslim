<?php

namespace App\Models;

use App\Interfaces\ArrayableInterface;
use App\Interfaces\ResourceableInterface;
use App\Resources\UserResource;
use DateTime;

class User extends Model implements ResourceableInterface
{
    private string $id;
    private string $name;
    private string $email;
    private string $password;
    private DateTime $createdAt;
    private ?DateTime $updatedAt;
    private ?DateTime $deactivatedAt;

    public function __construct()
    {
        $this->setColumns([
            'id',
            'name',
            'email',
            'password',
            'created_at',
            'updated_at',
            'deactivated_at'
        ]);
    }

    /**
     *  ----------------------------------------------------------------
     *  Getters and setters
     *  ----------------------------------------------------------------
     */

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    /**
     * setPassword hashs the given password then set it
     * 
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
    /**
     * @param mixed $createdAt
     * @return void
     */
    public function setCreatedAt(mixed $createdAt): void
    {
        $this->createdAt = is_string($createdAt) ? new DateTime($createdAt) : $createdAt;
    }

    /**
     * @return ?DateTime
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt ?? null;
    }
    /**
     * @param mixed $updatedAt
     * @return void
     */
    public function setUpdatedAt(mixed $updatedAt): void
    {
        $this->updatedAt = is_string($updatedAt) ? new DateTime($updatedAt) : $updatedAt;
    }

    /**
     * @return ?DateTime
     */
    public function getDeactivatedAt(): ?DateTime
    {
        return $this->deactivatedAt ?? null;
    }
    /**
     * @param mixed $deactivatedAt
     * @return void
     */
    public function setDeactivatedAt(mixed $deactivatedAt): void
    {
        $this->deactivatedAt = is_string($deactivatedAt) ? new DateTime($deactivatedAt) : $deactivatedAt;
    }

    /**
     *  ----------------------------------------------------------------
     *  Other methods
     *  ----------------------------------------------------------------
     */

    public function toResource(): ArrayableInterface
    {
        return new UserResource($this);
    }
}
