<?php
namespace Framework\Routing;

use Psr\Http\Message\ServerRequestInterface;

class Route
{
    private string $method;
    private string $path;
    private string $handler;

    public function __construct(string $method, string $path, string $handler)
    {
        $this->method  = strtoupper($method);
        $this->path    = $path;
        $this->handler = $handler;
    }

    /**
     * Bepaalt of deze route overeenkomt met de huidige request.
     */
    public function matches(ServerRequestInterface $request): bool
    {
        $requestPath = parse_url((string)$request->getUri(), PHP_URL_PATH);
        return $this->method === strtoupper($request->getMethod())
            && $this->path === $requestPath;
    }

    /**
     * @return string De controller-class die deze route moet afhandelen
     */
    public function getHandler(): string
    {
        return $this->handler;
    }
}
