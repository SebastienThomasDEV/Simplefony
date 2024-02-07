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
        if ($_ENV['TYPE'] === 'STATIC') {
            Router::dispatch();
        } else if ($_ENV['TYPE'] === 'API') {
            echo 'API';
        }
    }

}