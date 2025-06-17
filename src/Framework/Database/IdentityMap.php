<?php
namespace Framework\Database;

class IdentityMap implements IdentityMapInterface
{
    /** @var array<string,array<mixed,object>> */
    private array $map = [];

    public function set(string $class, mixed $id, object $object): void
    {
        $key = $this->normalizeKey($class);
        $this->map[$key][$id] = $object;
    }

    public function get(string $class, mixed $id): ?object
    {
        $key = $this->normalizeKey($class);
        return $this->map[$key][$id] ?? null;
    }

    public function clear(): void
    {
        $this->map = [];
    }

    private function normalizeKey(string $class): string
    {
        return ltrim($class, '\\');
    }
}
