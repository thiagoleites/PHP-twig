<?php

declare(strict_types=1);

namespace App;

use Exception;

// TODO implement documentation
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

        throw new Exception("Route '{$name}' not found");
    }

    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchUri($route['uri'], $uri, $params)) {
                $controller = new $route['controller']();
                $action = $route['action'];

                if (method_exists($controller, $action)) {
                    $controller->{$action}(...$params);
                } else {
                    $this->sendResponse(500, "500 - Método '{$action}' não encontrado no controlador '{$route['controller']}'");
                }
                return;
            }
        }

        $this->sendResponse(404, '404 - Página não encontrada');
    }

    private function matchUri(string $routeUri, string $requestUri, ?array &$params = []): bool
    {
        $routeParts = explode('/', trim($routeUri, '/'));
        $requestParts = explode('/', trim($requestUri, '/'));

        if (count($routeParts) !== count($requestParts)) {
            return false;
        }

        $params = [];

        foreach ($routeParts as $index => $part) {
            if (preg_match('/^{\w+}$/', $part)) {
                $params[] = $requestParts[$index];
            } elseif ($part !== $requestParts[$index]) {
                return false;
            }
        }

        return true;
    }

    private function sendResponse(int $statusCode, string $message): void
    {
        http_response_code($statusCode);
        echo $message;
    }
}
