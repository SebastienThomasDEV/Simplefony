<?php

namespace Mvc\Framework\Kernel;


use Mvc\Framework\Kernel\Utils\Utils;

class ApiRouter
{

    private static array $endpoints = [];

    public static function searchForRoutes(): void
    {

        $urn = $_SERVER["REQUEST_URI"];
        self::register();
        self::load($urn);
    }


    private static function register(): void
    {
        // j'ouvre le dossier Controller de mon application
        $dir = opendir(__DIR__ . '/../../src/App/Controller');
        // je fait une boucle tant que pour lire les fichers qu'il contient
        while ($file_path = readdir($dir)) {
            if ($file_path !== '.' && $file_path !== '..') {
                $file_path = str_replace('.php', '', $file_path);
                $file_path = 'Mvc\\Framework\\App\\Controller\\' . $file_path;
                try {
                    $class = new \ReflectionClass($file_path);
                    $methods = $class->getMethods();
                    foreach ($methods as $method) {
                        $attributes = $method->getAttributes();
                        $parameters = $method->getParameters();
                        foreach ($attributes as $attribute) {
                            if ($attribute->getName() === 'Mvc\\Framework\\Kernel\\Attributes\\Endpoint') {
                                $endpoint = $attribute->newInstance();
                                $endpoint->setController($file_path);
                                $endpoint->setMethod($method->getName());
                                foreach ($parameters as $parameter) {
                                    if (!Utils::isPrimitiveFromString($parameter->getType())) {
                                        $endpoint->setParameter($parameter->getName(), $parameter->getType());
                                    }
                                }
                                self::$endpoints[] = $endpoint;
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
        $test = str_replace(' ', '', $test);
        $urn = str_replace('/' . strtolower($test), '', $urn);
        $params = explode('?', $urn);
        if (count($params) > 1) {
            $urn = $params[0];
            $params = $params[1];
        } else {
            $params = null;
        }
        

        $endpointFound = null;
        foreach (self::$endpoints as $endpoint) {
            if ($endpoint->getPath() === $urn) {
                if ($endpoint->getRequestMethod() === $_SERVER['REQUEST_METHOD']) {
                    $endpointFound = $endpoint;
                } else {
                    echo 'method not allowed';
                    die();
                }
            }
        }
        if (!$endpointFound) {
            echo 'endpoint not found';
            die();
        } else {
            if (class_exists($endpointFound->getController())) {
                $controller = new \ReflectionClass($endpointFound->getController());
                try {
                    $controller = $controller->newInstance();
                    if (method_exists($controller, $endpointFound->getMethod())) {
                        $method = $endpointFound->getMethod();
                        $services = DependencyResolver::resolve($endpointFound->getParameters());
                        $controller->$method(...$services);
                    }
                } catch (\ReflectionException $e) {
                    echo $e->getMessage();
                    die();
                }
            }
        }
    }


}
