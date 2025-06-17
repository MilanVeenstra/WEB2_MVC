<?php
namespace Framework\EventDispatcher;

/**
 * Eenvoudige PSR-14 Event Dispatcher
 */
interface EventDispatcherInterface
{
    /**
     * Dispatch het gegeven event aan alle listeners
     *
     * @param object $event
     * @return object Het (mogelijk) gewijzigde event
     */
    public function dispatch(object $event): object;
}
