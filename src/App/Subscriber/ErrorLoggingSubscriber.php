<?php
namespace App\Subscriber;

use Framework\EventDispatcher\EventSubscriberInterface;
use Framework\EventDispatcher\ExceptionEvent;
use Psr\Log\LoggerInterface;

class ErrorLoggingSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger) {}

    public static function getSubscribedEvents(): array
    {
        // Wanneer er een ExceptionEvent dispatch wordt, wil
        // deze subscriber onException() aanroepen.
        return [
            ExceptionEvent::class => 'onException'
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        $ex = $event->getException();
        $this->logger->error('Uncaught exception: ' . $ex->getMessage(), [
            'exception' => $ex,
        ]);
    }
}
