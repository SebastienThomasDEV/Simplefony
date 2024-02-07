<?php

namespace Mvc\Framework\Core\Http;

abstract class HttpResponse
{
    public function __construct(private string $content = '', private int $status = 200, private array $headers = [])
    {}

    public function send()
    {

        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->content;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setStatusCode(int $status)
    {
        $this->status = $status;
    }

    public function setHeader(string $name, string $value)
    {
        $this->headers[$name] = $value;
    }


}