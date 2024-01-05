<?php

namespace App\Forms\Users;

class UserForm
{
    private int $id;
    private string $name;
    private string $email;
    private ?string $password;
    private ?\DateTime $deactivedAt;

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(mixed $id): self
    {
        $this->id = intval($id);
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return isset($this->password) && $this->password != '' ? $this->password : null;
    }
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getDeactivedAt(): ?\DateTime
    {
        return $this->deactivedAt ?? null;
    }
    public function setDeactivedAt(mixed $deactivedAt): self
    {
        if (is_null($deactivedAt) || $deactivedAt == '') {
            $this->deactivedAt = null;
        } else if (is_string($deactivedAt)) {
            $this->deactivedAt = \DateTime::createFromFormat('Y-m-d', $deactivedAt);
        } else if ($deactivedAt instanceof \DateTime) {
            $this->deactivedAt = $deactivedAt;
        }
        return $this;
    }
}
