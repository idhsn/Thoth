<?php
declare(strict_types=1);

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, array $action): void
    {
        $this->routes['GET'][] = [$path, $action];
    }

    public function post(string $path, array $action): void
    {
        $this->routes['POST'][] = [$path, $action];
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $method = strtoupper($method);

        foreach ($this->routes[$method] ?? [] as [$routePath, $action]) {
            $params = $this->match($routePath, $path);
            if ($params === null) {
                continue;
            }

            [$controllerName, $methodName] = $action;

            require_once __DIR__ . '/../controllers/' . $controllerName . '.php';

            $controller = new $controllerName();
            $controller->$methodName(...array_values($params));
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }

    private function match(string $routePath, string $requestPath): ?array
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        if (!preg_match($pattern, $requestPath, $matches)) {
            return null;
        }

        $params = [];
        foreach ($matches as $key => $value) {
            if (!is_string($key)) continue;
            $params[$key] = $value;
        }
        return $params;
    }
}
