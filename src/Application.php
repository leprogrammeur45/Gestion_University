<?php

namespace App;

use Closure;
use FastRoute\Dispatcher;
// Dispatcher trouver la route->controller
// closure recuperation
class Application
{
    public function __construct(
        private readonly Dispatcher $dispatcher,
        private readonly Closure $controllerResolver
    ) {
    }

    public function run(): void
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];

        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {

            case Dispatcher::NOT_FOUND:
                http_response_code(404);
                require dirname(__DIR__) . '/templates/error/404.php';
                break;

            case Dispatcher::METHOD_NOT_ALLOWED:
                http_response_code(405);
                header('Allow: ' . implode(', ', $routeInfo[1]));
                require dirname(__DIR__) . '/templates/error/405.php';
                break;

            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                [$controllerClass, $method] = $handler;

                $controller = ($this->controllerResolver)($controllerClass);

                $controller->$method(...array_values($vars));

                break;
        }
    }
}