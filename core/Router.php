<?php
class Router
{
    private array $routes = [];

    public function get(string $pattern, string $controller, string $action): void
    {
        $this->add('GET', $pattern, $controller, $action);
    }

    public function post(string $pattern, string $controller, string $action): void
    {
        $this->add('POST', $pattern, $controller, $action);
    }

    private function add(string $method, string $pattern, string $controller, string $action): void
    {
        $this->routes[] = compact('method', 'pattern', 'controller', 'action');
    }

    public function dispatch(string $controllerDir): void
    {
        $method  = $_SERVER['REQUEST_METHOD'];
        $basePath = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
        $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

        if ($basePath !== '' && strncasecmp($requestPath, $basePath, strlen($basePath)) === 0) {
            $requestPath = substr($requestPath, strlen($basePath)) ?: '/';
        }

        $uri = '/' . trim($requestPath, '/');
        $uri = preg_replace('#/index\.php$#i', '', $uri) ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $route['pattern']) . '$#';

            if (preg_match($regex, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                $file = $controllerDir . '/' . $route['controller'] . '.php';
                if (!file_exists($file)) {
                    http_response_code(500);
                    die("Controller not found: {$route['controller']}");
                }

                require_once BASE_PATH . '/core/Model.php';
                require_once $file;

                $obj = new $route['controller']();
                call_user_func_array([$obj, $route['action']], $params);
                return;
            }
        }

        http_response_code(404);
        // Try city 404 first, fallback to admin 404
        $city404 = defined('CITY_DIR') ? CITY_DIR . '/views/errors/404.php' : null;
        if ($city404 && file_exists($city404)) {
            require $city404;
        } else {
            require BASE_PATH . '/admin/views/errors/404.php';
        }
    }
}
