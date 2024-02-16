<?php

namespace Mvc\Framework\Core\Utils;

use Mvc\Framework\Core\Utils;

class Request extends Utils
{

    // create a new request like $_GET, $_POST, $_FILES, $_COOKIE, $_SESSION, $_SERVER
    public array $get;
    public array $post;
    public array $files;
    public array $cookie;
    public array $session;
    public array $server;


    public function __construct()
    {
        $this->init();
    }

    public function init(): void
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->cookie = $_COOKIE;
        $this->session = $_SESSION;
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

    public function files(string $key, mixed $default = null): mixed
    {
        return $this->files[$key] ?? $default;
    }

    public function cookie(string $key, mixed $default = null): mixed
    {
        return $this->cookie[$key] ?? $default;
    }

    public function session(string $key, mixed $default = null): mixed
    {
        return $this->session[$key] ?? $default;
    }

    public function server(string $key, mixed $default = null): mixed
    {
        return $this->server[$key] ?? $default;
    }


}