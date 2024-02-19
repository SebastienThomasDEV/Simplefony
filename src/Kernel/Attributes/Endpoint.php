<?php

namespace Mvc\Framework\Kernel\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class Endpoint
{

    private string $method;
    private string $requestMethod;
    private string $controller;

    private array $parameters = [];

    public function __construct(private readonly string $path, private readonly string $name){}

    public final function getPath(): string
    {
        return $this->path;
    }

    public final function getMethod(): string
    {
        return $this->method;
    }

    public final function getController(): string
    {
        return $this->controller;
    }

    public final function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public final function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    public final function getName(): string
    {
        return $this->name;
    }


    public final function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    public final function setRequestMethod(string $requestMethod): void
    {
        $this->requestMethod = $requestMethod;
    }

    public final function setParameter(string $parameter, string $value)  {
        $this->parameters[$parameter] = $value;
    }

    public final function getParameter(string $parameter) {
        return $this->parameters[$parameter];
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }



}