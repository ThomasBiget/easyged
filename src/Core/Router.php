<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post(string $path, callable $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    public function put(string $path, callable $action): void
    {
        $this->routes['PUT'][$path] = $action;
    }

    public function delete(string $path, callable $action): void
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

        $action = $this->routes[$method][$uri];

        call_user_func($action);
    }
}
