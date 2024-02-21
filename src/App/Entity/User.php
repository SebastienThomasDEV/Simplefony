<?php

namespace Mvc\Framework\App\Entity;

use Mvc\Framework\Kernel\Model\UserInterface;

class User implements UserInterface
{
    private int $id;
    private string $username;
    private string $password;
    private string $email;
    private array $roles;

    public function __construct(){}

    public final function getId(): int
    {
        return $this->id;
    }

    public final function getUsername(): string
    {
        return $this->username;
    }

    public final function getPassword(): string
    {
        return $this->password;
    }

    public final function getEmail(): string
    {
        return $this->email;
    }

    public final function getRoles(): array
    {
        return $this->roles;
    }

    public final function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public final function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public final function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public final function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

}
