<?php

namespace Mvc\Framework\Kernel\Model;

interface UserInterface
{

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public function getId(): int;

    public function getUsername(): string;

    public function getPassword(): string;

    public function getEmail(): string;

    public function getRoles(): array;

    public function setUsername(string $username): void;

    public function setPassword(string $password): void;

    public function setEmail(string $email): void;

    public function setRoles(array $roles): void;



}