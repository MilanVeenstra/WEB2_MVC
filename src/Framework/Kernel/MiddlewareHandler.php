<?php
namespace Framework\Kernel;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class MiddlewareHandler implements RequestHandlerInterface
{
    public function __construct(
        private MiddlewareInterface     $middleware,
        private RequestHandlerInterface $next
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middleware->process($request, $this->next);
    }
}
