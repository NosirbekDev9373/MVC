<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $url, $handler)
    {
        $this->addRoute('GET', $url, $handler);
    }

    public function post(string $url, $handler)
    {
        $this->addRoute('POST', $url, $handler);
    }

    private function addRoute(string $method, string $url, $handler)
    {
        $this->routes[] = compact('method', 'url', 'handler');
    }

    public function dispatch()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '([^/]+)', $route['url']);
            $pattern = "#^" . $pattern . "$#";

            if ($method !== $route['method']) continue;

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // full match

                // ✅ Admin route tekshiruvi - YANGI!
                if (strpos($route['url'], '/admin') === 0) {
                    AdminMiddleware::check();  // ← Admin bo'lishi kerak!
                }

                $handler = $route['handler'];

                if (is_callable($handler)) {
                    return call_user_func_array($handler, $matches);
                }

                if (is_string($handler)) {
                    // Controller@method
                    [$controller, $action] = explode('@', $handler);
                    $controller = "App\\Controllers\\{$controller}";
                    $ctrl = new $controller();
                        return call_user_func_array([$ctrl, $action], $matches);
                }
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}