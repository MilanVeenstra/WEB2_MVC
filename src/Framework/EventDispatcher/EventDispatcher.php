<?php
namespace Framework\EventDispatcher;

class EventDispatcher implements EventDispatcherInterface
{
    /** @var array<class-string, array<int,array{0:EventSubscriberInterface,1:string}>> */
    private array $listeners = [];

    /**
     * @param iterable<EventSubscriberInterface> $subscribers
     */
    public function __construct(iterable $subscribers)
    {
        foreach ($subscribers as $subscriber) {
            foreach ($subscriber::getSubscribedEvents() as $eventClass => $method) {
                $this->listeners[$eventClass][] = [$subscriber, $method];
            }
        }
    }

    public function dispatch(object $event): object
    {
        $eventClass = get_class($event);
        if (! empty($this->listeners[$eventClass])) {
            foreach ($this->listeners[$eventClass] as [$subscriber, $method]) {
                $subscriber->$method($event);
            }
        }
        return $event;
    }
}
