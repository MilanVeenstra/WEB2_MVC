<?php
namespace Framework\Middleware;

use Framework\AccessControl\AuthenticationInterface;
use Framework\Kernel\MiddlewareInterface;
use Framework\Kernel\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(private AuthenticationInterface $auth) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->auth->authenticate();
        $user = $this->auth->getUser();
        // user in request‐attribuut beschikbaar maken
        return $handler->handle($request->withAttribute('user', $user));
    }
}
