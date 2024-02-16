<?php

namespace Mvc\Framework\Core;

use Dotenv\Dotenv;

class Kernel
{
    public function __construct() {
        $this->init();
    }

    private function init(): void
    {
        Dotenv::createImmutable(__DIR__ . '/../../')->load();
        Router::dispatch();
    }

}