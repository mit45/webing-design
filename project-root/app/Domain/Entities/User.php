<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Email;

class User
{
    private ?int $id;
    private string $name;
    private Email $email;
    private string $passwordHash;
    private int $roleId;
    private string $status;
    private string $createdAt;
    private ?string $updatedAt;

    public function __construct(?int $id, string $name, Email $email, string $passwordHash, int $roleId = 2, string $status = 'active')
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->roleId = $roleId;
        $this->status = $status;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
