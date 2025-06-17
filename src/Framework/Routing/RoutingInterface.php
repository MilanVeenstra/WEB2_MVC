<?php
namespace Framework\Routing;

use Psr\Http\Message\ServerRequestInterface;

interface RoutingInterface
{
    /**
     * Voeg een route toe.
     *
     * @param string $method  HTTP-methode (GET, POST, etc.)
     * @param string $path    URI-pad (bijv. '/articles/{id}')
     * @param string $handler Klassestring van controller (met namespace)
     */
    public function add(string $method, string $path, string $handler): void;

    /**
     * Zoek de bijpassende route voor deze request.
     *
     * @throws \RuntimeException  Indien geen route matched (404)
     */
    public function match(ServerRequestInterface $request): Route;
}
