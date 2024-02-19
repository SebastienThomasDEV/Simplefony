<?php

namespace Mvc\Framework\Kernel\Utils;

class Request
{

    public array $get;
    public array $post;


    public function __construct()
    {
        $this->detectRequestBody();
        $this->get = $_GET;
        $this->post = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    }

    public static function createFromGlobals(): self
    {
        return new self();
    }

    public final function get(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    public final function post(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    private function detectRequestBody(): void
    {
        $rawInput = fopen('php://input', 'r');
        $tempStream = fopen('php://temp', 'r+');
        stream_copy_to_stream($rawInput, $tempStream);
        rewind($tempStream);
    }




}