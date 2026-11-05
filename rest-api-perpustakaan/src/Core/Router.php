<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, callable $callback): void
    {
        $this->routes['GET'][$uri] = $callback;
    }

    public function post(string $uri, callable $callback): void
    {
        $this->routes['POST'][$uri] = $callback;
    }

    public function put(string $uri, callable $callback): void
    {
        $this->routes['PUT'][$uri] = $callback;
    }

    public function delete(string $uri, callable $callback): void
    {
        $this->routes['DELETE'][$uri] = $callback;
    }

    public function resolve(Request $request): void
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        foreach ($this->routes[$method] ?? [] as $route => $callback) {
            $pattern = preg_replace('/\{id\}/', '([0-9]+)', $route);

            if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
                if (isset($matches[1])) {
                    call_user_func($callback, $request, (int) $matches[1]);
                } else {
                    call_user_func($callback, $request);
                }

                return;
            }
        }

        Response::error("Endpoint tidak ditemukan: {$uri}", 404);
    }
}
