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
                                $endpoint->setRequestMethod($endpoint->getRequestMethod());
                                foreach ($parameters as $parameter) {
                                    if (!PrimitiveTypes::isPrimitiveFromString($parameter->getType())) {
                                        $parameter_name = explode('\\', $parameter->getType());
                                        $parameter_name = strtolower(end($parameter_name));
                                        $endpoint->setParameter($parameter_name, $parameter->getType());
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
        $urn = str_replace('/' . $test, '', $urn);
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
                        // regarder ici si la dependance est un objet ou un type primitif
                        // voir https://www.php.net/manual/fr/functions.arguments.php pour l'ajout de parametre dynamic*
                        $services = DependencyInjectionContainer::resolve($endpointFound->getParameters());
                        $controller->$method(...$services);
                    }
                } catch (\ReflectionException $e) {
                    echo $e->getMessage();
                }
            }
        }
    }


}
