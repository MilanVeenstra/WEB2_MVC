<?php
declare(strict_types=1);

namespace Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Framework\Middleware\FirewallMiddleware;
use Framework\AccessControl\FirewallInterface;
use Framework\Kernel\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class FirewallMiddlewareTest extends TestCase
{
    public function testProcessBlocksWhenFirewallReturnsResponse(): void
    {
        // 1) Firewall stub die een Response teruggeeft
        $resp = $this->createMock(ResponseInterface::class);
        $fw = $this->createMock(FirewallInterface::class);
        $fw->method('check')->willReturn($resp);

        // 2) Handler mag niet worden aangeroepen
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->never())->method('handle');

        // 3) Request stub
        $request = $this->createMock(ServerRequestInterface::class);

        // 4) Run middleware
        $mw = new FirewallMiddleware($fw);
        $result = $mw->process($request, $handler);

        // 5) Output is exact de firewall-response
        $this->assertSame($resp, $result);
    }

    public function testProcessPassesWhenFirewallReturnsNull(): void
    {
        $fw = $this->createMock(FirewallInterface::class);
        $fw->method('check')->willReturn(null);

        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
            ->method('handle')
            ->willReturn($response);

        $request = $this->createMock(ServerRequestInterface::class);

        $mw = new FirewallMiddleware($fw);
        $out = $mw->process($request, $handler);

        $this->assertSame($response, $out);
    }
}
