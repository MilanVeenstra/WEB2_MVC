<?php
declare(strict_types=1);

namespace Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Framework\Middleware\AuthenticationMiddleware;
use Framework\AccessControl\AuthenticationInterface;
use Framework\Kernel\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Model\User;

class AuthenticationMiddlewareTest extends TestCase
{
    public function testProcessAddsUserAttribute(): void
    {
        // 1) Maak een stub AuthenticationInterface
        $auth = $this->createMock(AuthenticationInterface::class);
        $auth->expects($this->once())->method('authenticate');
        // Simuleer een ingelogde User
        $dummyUser = new User(99, 'test', password_hash('pw', PASSWORD_DEFAULT));
        $auth->method('getUser')->willReturn($dummyUser);

        // 2) Maak een dummy next-handler dat de request teruggeeft als response
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
            ->method('handle')
            ->with($this->callback(function (ServerRequestInterface $req) use ($dummyUser) {
                // Controleer dat de 'user' attribute is gezet
                return $req->getAttribute('user') === $dummyUser;
            }))
            ->willReturn($this->createMock(ResponseInterface::class));

        // 3) Simuleer een ServerRequest zonder attributes
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('withAttribute')
            ->willReturnCallback(fn($name, $value) => self::cloneWithAttr($request, $name, $value));

        // 4) Run de middleware
        $mw = new AuthenticationMiddleware($auth);
        $mw->process($request, $handler);
    }

    private static function cloneWithAttr($req, $name, $value)
    {
        // eenvoudige clone die attributes opslaat
        $new = clone $req;
        $new->method('getAttribute')->willReturn($value);
        return $new;
    }
}
