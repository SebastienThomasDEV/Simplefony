<?php

namespace Mvc\Framework\Kernel;

class DependencyResolver
{
    private static array $services = [];

    public static function resolve(array $parameters): array
    {
        foreach ($parameters as $key => $file_path) {
            try {
                $class = new \ReflectionClass($file_path);
                $class = $class->newInstance();
                self::$services[$key] = $class;
            } catch (\ReflectionException $e) {
                echo $e->getMessage();
            }
        }
        return self::$services;
    }

    public static function get(string $key): object
    {
        return self::$services[$key];
    }

    public static function set(string $key, object $value): void
    {
        self::$services[$key] = $value;
    }

    public static function has(string $key): bool
    {
        return isset(self::$services[$key]);
    }

    public static function remove(string $key): void
    {
        unset(self::$services[$key]);
    }

    public static function clear(): void
    {
        self::$services = [];
    }

}