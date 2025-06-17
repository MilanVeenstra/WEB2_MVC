<?php
namespace Framework\Middleware;

use Framework\Kernel\MiddlewareInterface;
use Framework\Kernel\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class LoggingMiddleware implements MiddlewareInterface
{
    public function __construct(private LoggerInterface $logger) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Log de inkomende request
        $this->logger->info('Request', [
            'method' => $request->getMethod(),
            'uri'    => (string) $request->getUri(),
        ]);

        // Verwerk volgende middleware/controller
        $response = $handler->handle($request);

        // Log de outgoing response
        $this->logger->info('Response', [
            'status' => $response->getStatusCode(),
        ]);

        return $response;
    }
}
