<?php
namespace Framework\EventDispatcher;

/**
 * Een subscriber meldt hier voor welke eventklassen hij luistert
 */
interface EventSubscriberInterface
{
    /**
     * @return array<class-string, string>
     *   Key = event-FQCN, value = methode op deze subscriber
     */
    public static function getSubscribedEvents(): array;
}
