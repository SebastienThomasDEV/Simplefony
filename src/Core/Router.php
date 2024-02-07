<?php

namespace Mvc\Framework\Core;

use Mvc\Framework\Config\Config;

class Router
{

    private static array $routes = [];

    public static function dispatch(): void
    {

        // TODO: Need to change hardcoded root directory
        $urn = str_replace($_ENV['ROOTDIR'], '', $_SERVER['REQUEST_URI']);
        self::register();
        self::load($urn);
    }


    private static function register(): void
    {
        $dir = opendir(__DIR__ . '/../../src/Controller');
        while ($file_path = readdir($dir)) {
            if ($file_path !== '.' && $file_path !== '..') {
                $file_path = str_replace('.php', '', $file_path);
                $file_path = 'Mvc\Framework\Controller\\' . $file_path;
                try {
                    $class = new \ReflectionClass($file_path);
                    $methods = $class->getMethods();
                    foreach ($methods as $method) {
                        $attributes = $method->getAttributes();
                        foreach ($attributes as $attribute) {
                            if ($attribute->getName() === 'Mvc\\Framework\\Core\\Attributes\\Route') {
                                $route = $attribute->newInstance();
                                $route->setController($file_path);
                                $route->setMethod($method->getName());
                                self::$routes[] = $route;
                            } elseif ($attribute->getName() === 'Mvc\\Framework\\Core\\Attributes\\Endpoint') {
                                $endpoint = $attribute->newInstance();
                                $endpoint->setController($file_path);
                                $endpoint->setMethod($method->getName());
                                self::$routes[] = $endpoint;
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
        $routeFound = null;
        foreach (self::$routes as $route) {
            if ($route->getPath() === $urn) {
                $routeFound = $route;
            }
        }

        if (!$routeFound) {
            if ($_ENV['APP_ENV'] === 'PROD') {
                header('Location: ./');
            }
        } else {
            if (class_exists($routeFound->getController())) {
                $controller = new \ReflectionClass($routeFound->getController());
                try {
                    $controller = $controller->newInstance();
                    if (method_exists($controller, $routeFound->getMethod())) {
                        $method = $routeFound->getMethod();
                        $controller->$method();
                    }
                } catch (\ReflectionException $e) {
                    echo $e->getMessage();
                }
            }
        }
    }


}
