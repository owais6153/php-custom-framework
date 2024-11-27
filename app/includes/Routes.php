<?php
namespace App\Includes;
use Error;
use App\Controllers\Controller;

class Routes
{
    private static $routes = [];
    private static $currentGroup = null;
    private $method;
    private $route;
    private function __construct($method, $route)
    {
        $this->method = $method;
        $this->route = $route;
    }
    public static function get($route, $controller, $method)
    {
        return self::registerRoute('GET', $route, $controller, $method);
    }

    public static function post($route, $controller, $method)
    {
        return self::registerRoute('POST', $route, $controller, $method);
    }

    public static function put($route, $controller, $method)
    {
        return self::registerRoute('PUT', $route, $controller, $method);
    }

    public static function patch($route, $controller, $method)
    {
        return self::registerRoute('PATCH', $route, $controller, $method);
    }

    public static function delete($route, $controller, $method)
    {
        return self::registerRoute('DELETE', $route, $controller, $method);
    }

    public static function group($options, $callback)
    {
        $prefix = $options['prefix'] ?? '';
        $middlewares = $options['middleware'] ?? [];

        self::$currentGroup = ['prefix' => $prefix, 'middlewares' => $middlewares];

        $callback();

        self::$currentGroup = null;
    }

    private static function registerRoute($method, $route, $controller, $methodName)
    {
        $prefix = self::$currentGroup['prefix'] ?? '';
        $fullRoute = $prefix . $route;
        $groupMiddlewares = self::$currentGroup['middlewares'] ?? [];

        self::$routes[$method][$fullRoute] = [
            'controller' => $controller,
            'method' => $methodName,
            'middlewares' => $groupMiddlewares,
        ];

        return new static($method, $fullRoute);
    }

    public function middleware(array $middlewares)
    {
        self::$routes[$this->method][$this->route]['middlewares'] = array_merge(
            self::$routes[$this->method][$this->route]['middlewares'],
            $middlewares
        );
        return $this;
    }

    public static function resolve($request)
    {
        $method = $request->getMethod();
        $uri = $request->getURI();

        if (isset(self::$routes[$method][$uri])) {
            $route = self::$routes[$method][$uri];
            $middlewares = $route['middlewares'] ?? [];
            $controller = $route['controller'];
            $methodName = $route['method'];

            $pipeline = array_reduce(
                array_reverse($middlewares),
                function ($next, $middleware) {
                    return function ($request) use ($middleware, $next) {
                        return $middleware($request, $next);
                    };
                },
                function ($request) use ($controller, $methodName) {
                    // Final route handler
                    if (class_exists($controller) && method_exists($controller, $methodName)) {
                        $instance = new $controller();
                        return call_user_func([$instance, $methodName], $request);
                    } else {
                        throw new Error("Controller or method not found", 500);
                    }
                }
            );
            return $pipeline($request);
        } else {
            return call_user_func([new Controller(), "show404"], $request);
        }
    }
}