<?php

namespace Mvc\Framework\Kernel;

use Dotenv\Dotenv;

class Kernel
{
    public function __construct() {
        Dotenv::createImmutable(__DIR__ . '/../../')->load();
        ApiRouter::dispatch();
    }
}