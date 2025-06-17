<?php
namespace Framework\Database;

interface IdentityMapInterface
{
    /**
     * Sla een instantie op onder zijn klasse en primaire sleutel.
     *
     * @param string $class FQN van de entity
     * @param mixed  $id    De primaire sleutel
     * @param object $object De entity-instantie
     */
    public function set(string $class, mixed $id, object $object): void;

    /**
     * Haal een eerder opgeslagen instantie op.
     *
     * @param string $class FQN van de entity
     * @param mixed  $id    De primaire sleutel
     * @return object|null  De opgeslagen instantie, of null als niet gevonden
     */
    public function get(string $class, mixed $id): ?object;

    /**
     * Wis alle opgeslagen instanties (bv. per request).
     */
    public function clear(): void;
}
