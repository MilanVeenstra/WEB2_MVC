<?php
namespace Framework\DependencyInjection;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    private array $definitions;
    private array $instances = [];

    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    public function get(string $id): mixed
    {
        // 1) Al eerder opgebouwd?
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        // 2) Definiëring aanwezig?
        if (! array_key_exists($id, $this->definitions)) {
            throw new class("Service '{$id}' not found") extends \Exception implements NotFoundExceptionInterface {};
        }

        $def = $this->definitions[$id];
        // 3) Bouw service: als callable, roep het met $this; anders gebruik value
        $obj = is_callable($def) ? $def($this) : $def;

        // 4) Cache en return
        return $this->instances[$id] = $obj;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->definitions);
    }
}
