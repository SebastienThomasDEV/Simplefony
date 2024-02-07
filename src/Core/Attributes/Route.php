<?php

namespace Mvc\Framework\Core\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class Route
{

    private string $method;
    private string $controller;

    public function __construct(private readonly string $path, private readonly string $name){}

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function getUri(): string
    {
        return $this->uri;
    }

}