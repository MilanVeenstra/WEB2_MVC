<?php
declare(strict_types=1);

namespace Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Framework\Middleware\SessionStartMiddleware;
use Framework\Http\SessionInterface;
use Framework\Kernel\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class SessionStartMiddlewareTest extends TestCase
{
    public function testProcessStartsSessionAndCallsNext(): void
    {
        // 1) Maak een mock SessionInterface
        $session = $this->createMock(SessionInterface::class);
        // verwacht dat start() precies één keer wordt aangeroepen
        $session->expects($this->once())->method('start');

        // 2) Maak een dummy Response en handler
        $response = $this->createMock(ResponseInterface::class);
        $handler  = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
            ->method('handle')
            ->willReturn($response);

        // 3) Simuleer een ServerRequest
        $request = $this->createMock(ServerRequestInterface::class);

        // 4) Instantieer en run de middleware
        $mw = new SessionStartMiddleware($session);
        $out = $mw->process($request, $handler);

        // 5) Assert dat de output exactly de dummy Response is
        $this->assertSame($response, $out);
    }
}
