<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /**
 * @var array<string, array<string, array{0: string, 1: string}>>
 */

    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, string $controller, string $method):void
    {
        $this->routes['GET'][$this->normalize($path)] = [$controller, $method];

    }

    public function post(string $path,string $controller, string $method):void
    {
        $this->routes['POST'][$this->normalize($path)] = [$controller, $method];
    }

    public function dispatch(string $method, string $path): void
    {
       $path = $this->normalize(parse_url($path, PHP_URL_PATH) ?: '/');

       // Si el método HTTP no está entre los registrados (GET/POST),
       // responder 405 Method Not Allowed en lugar de provocar un
       // "Undefined array key" warning al acceder a $this->routes[$method].
       if (!isset($this->routes[$method])) {
           http_response_code(405);
           header('Allow: ' . implode(', ', array_keys($this->routes)));
           echo 'Método no permitido.';
           return;
       }

       $route = $this->routes[$method][$path] ?? null;
       
       if($route === null){
        http_response_code(404);
        echo 'Pagina no encontrada.';
        return;
       }

       [$controller, $action] = $route;
       (new $controller())->$action();
    }

    private function normalize(string $path): string
    {
        $path = '/' .trim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }
}