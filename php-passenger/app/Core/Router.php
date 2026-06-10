<?php
class Router {
    private array $routes = [];

    public function add(string $method, string $pattern, callable $handler): void {
        $this->routes[] = compact('method', 'pattern', 'handler');
    }

    public function dispatch(string $method, string $uri): void {
        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $route['pattern']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                call_user_func($route['handler'], $params);
                return;
            }
        }

        http_response_code(404);
        echo json_encode([
            'status'    => 'error',
            'code'      => 404,
            'data'      => null,
            'message'   => 'Route not found',
            'timestamp' => date('c'),
            'service'   => getenv('SERVICE_NAME') ?: 'passenger-service'
        ]);
    }
}