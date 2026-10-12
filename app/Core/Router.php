<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    /**
     * @var array<string, array<string, array{0: string, 1: string}>>
     */
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, string $controller, string $method): void
    {
        $this->routes['GET'][$this->normalize($path)] = [$controller, $method];
    }

    public function post(string $path, string $controller, string $method): void
    {
        $this->routes['POST'][$this->normalize($path)] = [$controller, $method];
    }

    public function dispatch(string $method, string $path): void
    {
        $rawPath = parse_url($path, PHP_URL_PATH) ?: '/';
        $base = defined('BASE_URL') ? rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?: '', '/') : '';
        if ($base !== '' && str_starts_with($rawPath, $base)) {
            $rawPath = substr($rawPath, strlen($base)) ?: '/';
        }
        $path = $this->normalize($rawPath);

        // 405 handling
        if (!isset($this->routes[$method])) {
            http_response_code(405);
            header('Allow: ' . implode(', ', array_keys($this->routes)));
            echo 'Método no permitido.';
            return;
        }

        // Exact match first
        $route = $this->routes[$method][$path] ?? null;
        if ($route !== null) {
            [$controller, $action] = $route;
            (new $controller())->$action();
            return;
        }

        // Dynamic route matching
        foreach ($this->routes[$method] as $routePath => $handler) {
            $pattern = $this->pathToRegex($routePath);
            if (preg_match($pattern, $path, $matches)) {
                // Extract named params into $_GET
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $_GET[$key] = $value;
                    }
                }
                [$controller, $action] = $handler;
                (new $controller())->$action();
                return;
            }
        }

        // No route found
        http_response_code(404);
        echo 'Pagina no encontrada.';
    }

    private function normalize(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }

    /**
     * Convert a route definition with {param} placeholders to a PCRE regex.
     * Example: '/despachos/{id}' => '#^/despachos/(?P<id>[^/]+)$#'
     */
    private function pathToRegex(string $path): string
    {
        $escaped = preg_replace('#/+#', '/', $path);
        $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $escaped);
        return '#^' . $regex . '$#';
    }
}
?>