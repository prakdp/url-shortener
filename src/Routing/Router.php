<?php

namespace App\Routing;

use App\Routing\Exception\RouteNotFoundException;
use http\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Simple regexp router
 */
class Router implements RouterInterface
{

    /**
     * Array of Routes
     *
     * @var Route[]
     */
    private $routes = [];


    /**
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        $this->importRoutes($routes);
    }


    /**
     * Add Route
     *
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }


    /**
     * Import routes from an array with following structure:
     * <path> => [<controller classname>, <action>, ['GET', 'POST, 'PUT', 'DELETE']]
     *
     * @param array $routes
     *
     * @throws InvalidArgumentException
     */
    private function importRoutes(array $routes)
    {
        if (empty($routes)) {
            return;
        }
        foreach ($routes as $path => $route) {
            // Check if route has 3 arguments
            if (count($route) != 3) {
                throw new InvalidArgumentException("Bad route - $path");
            }
            list($controller, $action, $methods) = $route;
            $this->addRoute(new Route($path, $methods, $controller, $action));
        }
    }


    /**
     * @param Request $request
     *
     * @return Match
     *
     * @throws RouteNotFoundException
     */
    public function match(Request $request) : Match
    {
        foreach ($this->routes as $name => $route) {
            // Check method
            if (!in_array($request->getMethod(), $route->getMethods())) {
                continue;
            }

            // Check pattern
            if (preg_match($route->getPath(), $request->getRequestUri(), $matches)) {
                return new Match($route->getController(), $route->getAction());
            }
        }
        throw new RouteNotFoundException($request);
    }
}
