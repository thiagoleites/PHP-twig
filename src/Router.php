<?php

declare(strict_types=1);

namespace App;

// use App\Controller\HomeController;

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $uri, string $controller, string $action): void
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'action' => $action,
        ];
    }

    public function dispatch(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            $uriParts = explode('/', $uri);
            if ($route['method'] === $method && $route['uri'] === '/'.$uriParts[2]) {
                $controller = new $route['controller']();
                $action = $route['action'];
                $controller->{$action}();
                return;
            }
        }

        http_response_code(404);
        echo '404 - Página não encontrada';

    }
}
