<?php

namespace Mvc\Framework\Core;

class DependencyInjectionContainer
{
    private static array $services = [];

    public function __invoke(): void
    {
        // TODO: Implement __invoke() method.
        self::$services = [
            'request' => fn() => new Utils\Request(),
        ];
    }

    public static function get(string $service): mixed
    {
        return self::$services[$service];
    }
}