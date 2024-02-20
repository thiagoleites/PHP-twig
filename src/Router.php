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
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'action' => $action,
        ];
    
        // Certifique-se de que você está registrando a rota corretamente no array $routeNames
        $this->routeNames[$name] = $uri;
    }

    public function getPath(string $name): string
    {
        echo "Debug: Obtendo caminho para a rota '$name'<br>";

        if (isset($this->routeNames) && isset($name)) {
            if (isset($this->routeNames[$name])) {
                $path = $this->routeNames[$name];
                echo "Debug: Caminho encontrado para a rota '$name': $path<br>";
                return $path;
            }
        }

        return '/';
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
