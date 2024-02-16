<?php

namespace Mvc\Framework\Core;

use Mvc\Framework\Core\Enums\PrimitiveTypes;

class Router
{

    private static array $endpoints = [];

    public static function dispatch(): void
    {

        // TODO: Need to change hardcoded root directory
        if (!empty($_SERVER)) {
            $urn = str_replace($_ENV['ROOTDIR'], '', $_SERVER['REQUEST_URI']);
        } else {
            $urn = '/';
        }
        self::register();
        self::load($urn);
    }


    private static function register(): void
    {
        $dir = opendir(__DIR__ . '/../../src/Controller');
        while ($file_path = readdir($dir)) {
            if ($file_path !== '.' && $file_path !== '..') {
                $file_path = str_replace('.php', '', $file_path);
                $file_path = 'Mvc\\Framework\\Controller\\' . $file_path;
                try {
                    $class = new \ReflectionClass($file_path);
                    dd($class);
                    $methods = $class->getMethods();
                    foreach ($methods as $method) {
                        $attributes = $method->getAttributes();
                        $parameters = $method->getParameters();

                        foreach ($attributes as $attribute) {
                            if ($attribute->getName() === 'Mvc\\Framework\\Core\\Attributes\\Endpoint') {
                                $endpoint = $attribute->newInstance();
                                $endpoint->setController($file_path);
                                $endpoint->setMethod($method->getName());
                                $endpoint->setRequestMethod($_SERVER['REQUEST_METHOD']);

                                if ($endpoint->getRequestMethod() === 'GET' || $endpoint->getRequestMethod() === 'POST' || $endpoint->getRequestMethod() === 'PUT' || $endpoint->getRequestMethod() === 'DELETE') {
                                    $endpoint->setRequestMethod($endpoint->getRequestMethod());
                                } else {
                                    $endpoint->setRequestMethod('GET');
                                }
                                self::$endpoints[] = $endpoint;
                            }
                        }
                        foreach ($parameters as $parameter) {
                            if (!PrimitiveTypes::isPrimitiveFromString($parameter->getType())) {
                                $type = explode('\\', $parameter->getType());
                                $type = strtolower(end($type));
                                $instance = DependencyInjectionContainer::get($type);
                                dd($instance);
                            }
                        }
                    }

                } catch (\ReflectionException $e) {
                    echo $e->getMessage();
                }
            }
        }
        closedir($dir);
    }

    private static function load(string $urn): void
    {

        $test = explode('\\', dirname(__DIR__, 2));
        $test = end($test);
        $test = strtolower($test);
        $test = str_replace(' ', '', $test);
        $urn = str_replace('/' . $test, '', $urn);
        $endpointFound = null;
        foreach (self::$endpoints as $endpoint) {
            if ($endpoint->getPath() === $urn) {
                $endpointFound = $endpoint;
            }
        }

        if (!$endpointFound) {
            echo 'endpoint not found';
        } else {
            if (class_exists($endpointFound->getController())) {
                $controller = new \ReflectionClass($endpointFound->getController());
                try {
                    $controller = $controller->newInstance();
                    if (method_exists($controller, $endpointFound->getMethod())) {
                        $method = $endpointFound->getMethod();
                        $controller->$method();
                    }
                } catch (\ReflectionException $e) {
                    echo $e->getMessage();
                }
            }
        }
    }


}
