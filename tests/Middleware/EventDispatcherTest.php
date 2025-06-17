<?php
declare(strict_types=1);

namespace Tests\EventDispatcher;

use PHPUnit\Framework\TestCase;
use Framework\EventDispatcher\EventDispatcher;
use Framework\EventDispatcher\ExceptionEvent;
use App\Subscriber\ErrorLoggingSubscriber;
use Psr\Log\LoggerInterface;

class EventDispatcherTest extends TestCase
{
    public function testExceptionEventIsLogged(): void
    {
        // 1) Vang logger-calls
        $calls = [];
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('error')
            ->with(
                'Uncaught exception: TestEx',
                $this->arrayHasKey('exception')
            );

        // 2) Maak subscriber en dispatcher
        $subscriber = new ErrorLoggingSubscriber($logger);
        $dispatcher = new EventDispatcher([$subscriber]);

        // 3) Dispatch een ExceptionEvent
        $ex = new \Exception('TestEx');
        $event = new ExceptionEvent($ex);
        $out   = $dispatcher->dispatch($event);

        // 4) Het teruggegeven event is exact dezelfde
        $this->assertSame($event, $out);
    }
}
