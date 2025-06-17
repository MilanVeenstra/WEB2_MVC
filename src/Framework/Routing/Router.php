<?php
namespace Framework\Routing;

use Psr\Http\Message\ServerRequestInterface;

class Router implements RoutingInterface
{
    /** @var Route[] */
    private array $routes = [];

    public function add(string $method, string $path, string $handler): void
    {
        $this->routes[] = new Route($method, $path, $handler);
    }

    public function match(ServerRequestInterface $request): Route
    {
        foreach ($this->routes as $route) {
            if ($route->matches($request)) {
                return $route;
            }
        }

        // Geen match: 404
        throw new \RuntimeException('Route not found', 404);
    }
}
