<?php
declare(strict_types=1);

namespace app\common;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, array $handler, array $middlewares = []): void
    {
        $this->routes[] = compact('method', 'path', 'handler', 'middlewares');
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($method) && $route['path'] === $path) {
                foreach ($route['middlewares'] as $middleware) {
                    $middleware::handle();
                }
                [$class, $action] = $route['handler'];
                (new $class())->{$action}();
                return;
            }
        }
        Response::json(404, 'not found', null, 404);
    }
}
