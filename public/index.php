<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

$container = require __DIR__ . '/../config/container.php';

$dispatcher = simpleDispatcher(
    require __DIR__ . '/../routes/web.php'
);

$httpMethod = $_SERVER['REQUEST_METHOD'];

$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {

    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        require __DIR__ . '/../templates/error/404.php';
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        header('Allow: ' . implode(', ', $routeInfo[1]));
        require __DIR__ . '/../templates/error/405.php';
        break;

    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controllerClass, $method] = $handler;

        $controller = $container->get($controllerClass);

        $controller->$method(...array_values($vars));
        break;
}
