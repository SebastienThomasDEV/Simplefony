<?php

namespace Mvc\Framework\App\Entity;


class Product
{
    private int $id;
    private string $name;
    private string $description;
    private float $price;

    public function __construct(){}

    public final function getName(): string
    {
        return $this->name;
    }

    public final function setName(string $name): void
    {
        $this->name = $name;
    }

    public final function getDescription(): string
    {
        return $this->description;
    }

    public final function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public final function getPrice(): float
    {
        return $this->price;
    }

    public final function setPrice(float $price): void
    {
        $this->price = $price;
    }

}