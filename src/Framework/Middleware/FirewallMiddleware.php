<?php
namespace Framework\Middleware;

use Framework\AccessControl\FirewallInterface;
use Framework\Kernel\MiddlewareInterface;
use Framework\Kernel\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class FirewallMiddleware implements MiddlewareInterface
{
    public function __construct(private FirewallInterface $firewall) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $resp = $this->firewall->check($request);
        if ($resp !== null) {
            return $resp;
        }
        return $handler->handle($request);
    }
}
