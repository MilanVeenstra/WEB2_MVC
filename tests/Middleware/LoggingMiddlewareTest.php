<?php
declare(strict_types=1);

namespace Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Framework\Middleware\LoggingMiddleware;
use Psr\Log\LoggerInterface;
use Framework\Kernel\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoggingMiddlewareTest extends TestCase
{
    public function testProcessLogsRequestAndResponse(): void
    {
        // Array to capture calls
        $calls = [];

        // Create mock logger and capture calls via callback
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))
            ->method('info')
            ->willReturnCallback(function(string $message, array $context) use (&$calls) {
                $calls[] = ['message' => $message, 'context' => $context];
            });

        // Handler returning dummy response
        $response = $this->createMock(ResponseInterface::class);
        $handler  = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response);

        // Fake URI object
        $uri = new class implements \Psr\Http\Message\UriInterface {
            public function getScheme(){}
            public function getAuthority(){}
            public function getUserInfo(){}
            public function getHost(){}
            public function getPort(){}
            public function getPath(){ return '/test'; }
            public function getQuery(){}
            public function getFragment(){}
            public function withScheme($s){}
            public function withUserInfo($u, $p = null){}
            public function withHost($h){}
            public function withPort($p){}
            public function withPath($p){}
            public function withQuery($q){}
            public function withFragment($f){}
            public function __toString(){ return '/test'; }
        };

        // Mock ServerRequest
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('GET');
        $request->method('getUri')->willReturn($uri);

        // Execute middleware
        $middleware = new LoggingMiddleware($logger);
        $out = $middleware->process($request, $handler);

        // Ensure the response is unchanged
        $this->assertSame($response, $out);

        // Assert logging calls order and data
        $this->assertCount(2, $calls);
        $this->assertEquals('Request', $calls[0]['message']);
        $this->assertArrayHasKey('method', $calls[0]['context']);
        $this->assertEquals('Response', $calls[1]['message']);
        $this->assertArrayHasKey('status', $calls[1]['context']);
    }
}
