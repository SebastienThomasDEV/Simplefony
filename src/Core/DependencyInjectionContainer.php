<?php

namespace Mvc\Framework\Core;

class DependencyInjectionContainer
{
    private static array $services = [];

    public static function resolve(array $parameters): array
    {
        foreach ($parameters as $key => $file_path) {
            $class = new \ReflectionClass($file_path);
            $class = $class->newInstance();
            self::$services[] = $class;
        }
        return self::$services;
    }

}