<?php

namespace Mvc\Framework\Kernel\Http;

abstract class HttpResponse
{
    public function __construct(private string $content = '', private int $status = 200, private array $headers = [])
    {}

    public final function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setStatusCode(int $status): void
    {
        $this->status = $status;
    }

    public function setHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }


}