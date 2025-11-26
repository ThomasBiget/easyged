<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post(string $path, string $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    public function put(string $path, string $action): void
    {
        $this->routes['PUT'][$path] = $action;
    }

    public function delete(string $path, string $action): void
    {
        $this->routes['DELETE'][$path] = $action;
    }

    public function dispatch(string $method, string $uri): void
    {
        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo json_encode([
                'error' => 'Route not found',
                'method' => $method,
                'uri' => $uri
            ]);
            return;
        }

        [$controllerName, $methodName] = explode('@', $this->routes[$method][$uri]);

        $controllerClass = "App\\Controllers\\{$controllerName}";

        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo json_encode(['error' => 'Controller not found']);
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $methodName)) {
            http_response_code(500);
            echo json_encode(['error' => 'Method not found in controller']);
            return;
        }

        call_user_func([$controller, $methodName]);
    }
}
