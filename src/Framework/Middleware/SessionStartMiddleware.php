<?php
namespace Framework\Middleware;

use Framework\Http\SessionInterface;
use Framework\Kernel\MiddlewareInterface;
use Framework\Kernel\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class SessionStartMiddleware implements MiddlewareInterface
{
    public function __construct(private SessionInterface $session) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->session->start();
        return $handler->handle($request);
    }
}
