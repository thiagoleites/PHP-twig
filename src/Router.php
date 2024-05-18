<?php

declare(strict_types=1);

namespace App;

class Router
{
    private array $routes = [];
    private array $routeNames = [];

    public function addRoute(string $name, string $method, string $uri, string $controller, string $action): void
    {
        $this->routes[] = [
            'name' => $name,
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'action' => $action,
        ];
    
        $this->routeNames[$name] = $uri;
    }

    public function getPath(string $name): string
    {
        if (isset($this->routeNames[$name])) {
            return $this->routeNames[$name];
        }

        return '/';
    }

    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $uri) {
                $controller = new $route['controller']();
                $action = $route['action'];
                
                if (method_exists($controller, $action)) {
                    $controller->{$action}();
                } else {
                    http_response_code(500);
                    echo "500 - Método '{$action}' não encontrado no controlador '{$route['controller']}'";
                }
                return;
            }
        }

        http_response_code(404);
        echo '404 - Página não encontrada';
    }
}
