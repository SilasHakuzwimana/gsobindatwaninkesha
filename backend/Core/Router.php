<?php

namespace Core;

class Router
{
  private $routes = [];

  /**
   * Add a route
   *
   * @param string $method HTTP method: GET, POST, PUT, PATCH, DELETE
   * @param string $pattern Route pattern, e.g. /api/students/:id
   * @param callable|array $callback Controller callback [ControllerClass, 'method']
   */
  public function add(string $method, string $pattern, $callback)
  {
    $this->routes[] = [
      'method' => strtoupper($method),
      'pattern' => $this->convertPattern($pattern),
      'callback' => $callback,
      'params' => $this->extractParams($pattern)
    ];
  }

  /**
   * Run the router and match the current request
   */
  public function run()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    foreach ($this->routes as $route) {
      if ($route['method'] !== $requestMethod) {
        continue;
      }

      if (preg_match($route['pattern'], $requestUri, $matches)) {
        $params = [];
        foreach ($route['params'] as $name) {
          if (isset($matches[$name])) {
            $params[$name] = $matches[$name];
          }
        }

        // Call controller method
        if (is_callable($route['callback'])) {
          call_user_func_array($route['callback'], $params);
        } elseif (is_array($route['callback'])) {
          [$controllerClass, $method] = $route['callback'];
          $controller = new $controllerClass();
          call_user_func_array([$controller, $method], $params);
        }
        return;
      }
    }

    // No route matched
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Route not found']);
  }

  /**
   * Convert a route pattern to a regex
   *
   * /api/students/:id -> #^/api/users/(?P<id>[^/]+)$#i
   */
  private function convertPattern(string $pattern): string
  {
    $pattern = preg_replace_callback('/:(\w+)/', function ($matches) {
      return '(?P<' . $matches[1] . '>[^/]+)';
    }, $pattern);

    return "#^$pattern$#i";
  }

  /**
   * Extract parameter names from pattern
   */
  private function extractParams(string $pattern): array
  {
    preg_match_all('/:(\w+)/', $pattern, $matches);
    return $matches[1] ?? [];
  }
}