<?php

namespace Mvc\Framework\Core\Utils;

use Mvc\Framework\Core\Utils;

class Request extends Utils
{

    public array $get;
    public array $post;
    public array $server;


    public function __construct()
    {
        $this->detectRequestBody();
        $this->get = $_GET;
        $this->post = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $this->server = $_SERVER;
    }

    public static function createFromGlobals(): self
    {
        return new self();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    private function detectRequestBody() {
        $rawInput = fopen('php://input', 'r');
        $tempStream = fopen('php://temp', 'r+');
        stream_copy_to_stream($rawInput, $tempStream);
        rewind($tempStream);

        return $tempStream;
    }




}