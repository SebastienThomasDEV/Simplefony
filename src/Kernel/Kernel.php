<?php

namespace Mvc\Framework\Kernel;

use Dotenv\Dotenv;

class Kernel
{
    public function __construct() {
        // notre package va recherché dans le fichier .env les variables qu'on aura définit et les charge
        // dans la super-globale PHP $_ENV (je passe le chemin vers mon fichier .env)
        Dotenv::createImmutable(__DIR__ . '/../../')->load();
        ApiRouter::searchForRoutes();
    }
}